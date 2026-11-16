<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmation;
use App\Mail\OrderReceivedAdmin;
use App\Models\Order;
use App\Models\SiteSetting;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
     * @return array{holder: string, iban: string, bic: string, bank_name: string, nif: string}
     */
    public static function bankDetails(): array
    {
        return SiteSetting::current()->bankDetails();
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
            'bank' => self::bankDetails(),
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
            'notes' => ['nullable', 'string', 'max:2000'],
            'payment_method' => ['required', Rule::in(array_keys(self::PAYMENT))],
            'transfer_proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf,webp', 'max:5120'],
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
            'transfer_proof.required' => 'Anexe o comprovativo da transferência bancária.',
            'transfer_proof.mimes' => 'O comprovativo deve ser JPG, PNG, WEBP ou PDF.',
            'transfer_proof.max' => 'O comprovativo não pode exceder 5 MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('checkout')
                ->withErrors($validator)
                ->withInput();
        }

        $data = $validator->validated();

        $shipMethod = 'ENTREGA';
        $payMethod = $data['payment_method'];
        $shipCost = self::SHIPPING[$shipMethod]['cost'];
        $subtotal = (float) $cart['subtotal'];
        $total = $subtotal + $shipCost;

        $proofPath = null;
        $proofName = null;
        if ($request->hasFile('transfer_proof')) {
            $file = $request->file('transfer_proof');
            $proofName = $file->getClientOriginalName();
            $proofPath = $file->store('transfer-proofs/'.now()->format('Y/m'), 'local');
        }

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
            'transfer_proof_path' => $proofPath,
            'transfer_proof_name' => $proofName,
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
        $order = Order::with('items')->where('ref', $ref)->firstOrFail();

        abort_unless(session('last_order_ref') === $ref || auth()->id() === $order->user_id, 403);

        return view('pages.checkout-sucesso', [
            'order' => $order,
            'shipping' => self::SHIPPING,
            'payment' => self::PAYMENT,
            'bank' => self::bankDetails(),
        ]);
    }

    private function makeRef(): string
    {
        do {
            $ref = 'DFP-'.now()->format('ymd').'-'.strtoupper(Str::random(4));
        } while (Order::where('ref', $ref)->exists());

        return $ref;
    }
}
