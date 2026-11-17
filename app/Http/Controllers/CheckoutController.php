<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmation;
use App\Mail\OrderReceivedAdmin;
use App\Models\Order;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Services\CartService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CheckoutController extends Controller
{
    public const SHIPPING = [
        'LEVANTAMENTO' => ['label' => 'Levantamento em loja', 'desc' => 'Grátis — pronto em 24-72h', 'cost' => 0.0],
        'ENTREGA' => ['label' => 'Entrega ao domicílio', 'desc' => 'Grátis — 1 a 3 dias úteis em todo o Portugal', 'cost' => 0.0],
    ];

    public const PAYMENT = [
        'VIREMENT' => [
            'label' => 'Transferência bancária',
            'desc' => 'Pagamento por transferência para a conta DFP Interiores',
        ],
    ];

    public function __construct(private CartService $cart) {}

    /**
     * @return array{holder: string, iban: string, bic: string, bank_name: string, nif: string, nib: string}
     */
    public static function bankDetails(): array
    {
        try {
            return SiteSetting::current()->bankDetails();
        } catch (\Throwable) {
            return [
                'holder' => (string) config('checkout.bank.holder', ''),
                'iban' => (string) config('checkout.bank.iban', ''),
                'bic' => (string) config('checkout.bank.bic', ''),
                'bank_name' => (string) config('checkout.bank.bank_name', ''),
                'nif' => (string) config('checkout.bank.nif', ''),
                'nib' => (string) config('checkout.bank.nib', ''),
            ];
        }
    }

    public function show()
    {
        $cart = $this->cart->toArray();
        if (empty($cart['items'])) {
            return redirect('/carrinho');
        }

        $user = auth()->user();

        return view('pages.checkout', [
            'cart' => $cart,
            'prefill' => [
                'name' => old('name', $user->name ?? ''),
                'email' => old('email', $user->email ?? ''),
                'phone' => old('phone', $user->phone ?? ''),
            ],
            'paymentOptions' => self::PAYMENT,
        ]);
    }

    public function place(Request $request)
    {
        $cart = $this->cart->toArray();
        if (empty($cart['items'])) {
            return redirect('/carrinho');
        }

        $billingSame = ! $request->boolean('billing_different');

        $rules = [
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:32'],
            'nif' => ['nullable', 'string', 'max:32'],
            'address' => ['required', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:16'],
            'city' => ['required', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:80'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'shipping_method' => ['nullable', Rule::in(array_keys(self::SHIPPING))],
            'payment_method' => ['required', Rule::in(array_keys(self::PAYMENT))],
            'terms' => ['accepted'],
        ] + ($billingSame ? [] : [
            'billing_name' => ['required', 'string', 'min:2', 'max:255'],
            'billing_address' => ['required', 'string', 'max:255'],
            'billing_postal_code' => ['nullable', 'string', 'max:16'],
            'billing_city' => ['required', 'string', 'max:255'],
        ]);

        $validator = Validator::make($request->all(), $rules, [
            'payment_method.required' => 'Selecione o método de pagamento.',
            'payment_method.in' => 'Método de pagamento inválido.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('checkout')
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();

        $district = trim((string) ($data['district'] ?? ''));
        if ($district !== '') {
            $withDistrict = $data['address'].', '.$district;
            if (mb_strlen($withDistrict) <= 255) {
                $data['address'] = $withDistrict;
            }
        }

        $shipMethod = $data['shipping_method'] ?? 'ENTREGA';
        $payMethod = $data['payment_method'];
        $shipCost = self::SHIPPING[$shipMethod]['cost'];
        $subtotal = (float) $cart['subtotal'];
        $total = $subtotal + $shipCost;

        $order = Order::create([
            'ref' => $this->makeRef(),
            'user_id' => auth()->id(),
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'nif' => $data['nif'] ?? null,
            'address' => $data['address'],
            'postal_code' => $data['postal_code'] ?? '',
            'city' => $data['city'],
            'billing_same' => $billingSame,
            'billing_name' => $billingSame ? null : $data['billing_name'],
            'billing_address' => $billingSame ? null : $data['billing_address'],
            'billing_postal_code' => $billingSame ? null : ($data['billing_postal_code'] ?? null),
            'billing_city' => $billingSame ? null : $data['billing_city'],
            'notes' => $data['notes'] ?? null,
            'shipping_method' => $shipMethod,
            'shipping_cost' => $shipCost,
            'payment_method' => $payMethod,
            'subtotal' => $subtotal,
            'total' => $total,
            'status' => 'AGUARDAR',
            'items_snapshot' => $cart['items'],
        ]);

        foreach ($cart['items'] as $it) {
            $order->items()->create([
                'product_id' => null,
                'name' => $it['nome'],
                'variation' => $it['variation'] ? collect($it['variation'])->map(fn ($v) => implode(': ', [array_key_first($v), reset($v)]))->implode(', ') : null,
                'unit_price' => $it['preco'],
                'qty' => $it['qtd'],
                'line_total' => $it['total'],
            ]);
        }

        $this->cart->clear();
        session(['last_order_ref' => $order->ref]);

        try {
            Mail::to($order->email)->send(new OrderConfirmation($order));
            Mail::to(config('mail.admin_address'))->send(new OrderReceivedAdmin($order));
        } catch (\Throwable $e) {
            Log::error('Envoi email commande '.$order->ref.' échoué : '.$e->getMessage());
        }

        return redirect()->route('checkout.success', $order->ref);
    }

    public function success(string $ref)
    {
        $order = $this->orderForCustomer($ref);

        $deliveryOn = self::deliveryDate($order);

        return view('pages.checkout-sucesso', [
            'order' => $order,
            'shipping' => self::SHIPPING,
            'payment' => self::PAYMENT,
            'bank' => self::bankDetails(),
            'similarProducts' => $this->similarProducts($order),
            'deliveryOn' => $deliveryOn,
            'deliveryText' => mb_ucfirst($deliveryOn->locale('pt')->translatedFormat('l, j \d\e F \d\e Y')),
            'deliveryLabel' => $order->shipping_method === 'LEVANTAMENTO' ? 'Data de levantamento' : 'Data de entrega',
        ]);
    }

    /**
     * Date ferme communiquée au client : 3 jours ouvrés après la réception du comprovativo.
     */
    public static function deliveryDate(Order $order): Carbon
    {
        $cursor = ($order->updated_at ?? now())->copy()->startOfDay();
        $remaining = 3;

        while ($remaining > 0) {
            $cursor->addDay();
            if ($cursor->isWeekday()) {
                $remaining--;
            }
        }

        return $cursor;
    }

    public function uploadProof(Request $request, string $ref)
    {
        $order = $this->orderForCustomer($ref);

        $request->validate([
            'transfer_proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf,webp', 'max:5120'],
        ], [
            'transfer_proof.required' => 'Anexe o comprovativo da transferência bancária.',
            'transfer_proof.mimes' => 'O comprovativo deve ser JPG, PNG, WEBP ou PDF.',
            'transfer_proof.max' => 'O comprovativo não pode exceder 5 MB.',
        ]);

        $previous = $order->transfer_proof_path;
        $file = $request->file('transfer_proof');
        $path = $file->store('transfer-proofs/'.now()->format('Y/m'), 'local');

        $order->update([
            'transfer_proof_path' => $path,
            'transfer_proof_name' => $file->getClientOriginalName(),
        ]);

        if (is_string($previous) && $previous !== '' && $previous !== $path) {
            Storage::disk('local')->delete($previous);
        }

        try {
            Mail::to(config('mail.admin_address'))->send(new OrderReceivedAdmin($order->fresh()));
        } catch (\Throwable $e) {
            Log::error('Envoi comprovativo '.$order->ref.' échoué : '.$e->getMessage());
        }

        return redirect()
            ->route('checkout.success', $order->ref)
            ->with('proof_sent', true);
    }

    private function similarProducts(Order $order): Collection
    {
        $snapshot = is_array($order->items_snapshot) ? $order->items_snapshot : [];
        $ids = [];
        $slugs = [];

        foreach ($snapshot as $item) {
            if (! is_array($item)) {
                continue;
            }
            if (is_string($item['seo'] ?? null) && $item['seo'] !== '') {
                $slugs[] = $item['seo'];
            }
            $line = (string) ($item['linhaid'] ?? '');
            $id = (int) strtok($line, ':');
            if ($id > 0) {
                $ids[] = $id;
            }
        }

        if ($ids === [] && $slugs === []) {
            return new Collection;
        }

        $sources = Product::query()
            ->with('categories')
            ->where(function ($query) use ($ids, $slugs): void {
                if ($ids !== []) {
                    $query->whereIn('id', $ids);
                }
                if ($slugs !== []) {
                    $ids === []
                        ? $query->whereIn('slug', $slugs)
                        : $query->orWhereIn('slug', $slugs);
                }
            })
            ->get();

        return Product::similarTo($sources);
    }

    private function orderForCustomer(string $ref): Order
    {
        $order = Order::with('items')->where('ref', $ref)->firstOrFail();

        abort_unless(session('last_order_ref') === $ref || auth()->id() === $order->user_id, 403);

        return $order;
    }

    private function makeRef(): string
    {
        do {
            $ref = 'DFP-'.now()->format('ymd').'-'.strtoupper(Str::random(4));
        } while (Order::where('ref', $ref)->exists());

        return $ref;
    }
}
