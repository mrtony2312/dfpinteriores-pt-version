<?php

namespace Tests\Feature;

use App\Mail\OrderConfirmation;
use App\Mail\OrderReceivedAdmin;
use App\Models\Order;
use App\Models\Product;
use App\Models\SiteSetting;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CheckoutPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_redirects_when_cart_empty(): void
    {
        $this->get(route('checkout'))
            ->assertRedirect('/carrinho');
    }

    public function test_checkout_page_renders_with_cart_item(): void
    {
        $product = Product::factory()->create([
            'name' => 'Sofá Teste Checkout',
            'price' => 199.90,
            'slug' => 'sofa-teste-checkout',
        ]);

        $this->app->make(CartService::class)->add($product->id, null, 1);

        $this->get(route('checkout'))
            ->assertOk()
            ->assertSee('Finalizar encomenda', false)
            ->assertSee('Sofá Teste Checkout', false)
            ->assertSee('Transferência bancária', false)
            ->assertSee('Email', false)
            ->assertDontSee('Informação de contacto', false)
            ->assertDontSee('Comprovativo de transferência', false);
    }

    public function test_checkout_places_order_with_transfer_proof_and_mails(): void
    {
        Storage::fake('local');
        Mail::fake();

        SiteSetting::query()->create([
            'bank_holder' => 'DFP Interiores Teste',
            'bank_iban' => 'PT50 1111 2222 3333 4444 5555 6',
            'bank_bic' => 'TESTPTPL',
            'bank_name' => 'Banco Teste',
            'bank_nif' => '504074571',
        ]);

        $product = Product::factory()->create([
            'name' => 'Sofá Pedido',
            'price' => 250.00,
            'slug' => 'sofa-pedido',
        ]);

        $this->app->make(CartService::class)->add($product->id, null, 1);

        $response = $this->post(route('checkout.place'), [
            'name' => 'Cliente Teste',
            'email' => 'cliente@example.com',
            'phone' => '912026453',
            'address' => 'Rua Teste 1',
            'postal_code' => '7080-035',
            'city' => 'Vendas Novas',
            'payment_method' => 'VIREMENT',
            'terms' => '1',
        ]);

        $order = Order::query()->first();
        $this->assertNotNull($order);
        $response->assertRedirect(route('checkout.success', $order->ref));
        $this->assertNull($order->transfer_proof_path);

        Mail::assertSent(OrderConfirmation::class, function (OrderConfirmation $mail) use ($order) {
            return $mail->hasTo('cliente@example.com') && $mail->order->is($order);
        });

        Mail::assertSent(OrderReceivedAdmin::class, function (OrderReceivedAdmin $mail) use ($order) {
            return $mail->order->is($order)
                && count($mail->attachments()) === 0;
        });

        $this->get(route('checkout.success', $order->ref))
            ->assertOk()
            ->assertSee('Faça a transferência de imediato', false)
            ->assertSee('PT50 1111 2222 3333 4444 5555 6', false)
            ->assertSee('Comprovativo de transferência', false)
            ->assertSee('Enviar', false);

        $this->post(route('checkout.proof', $order->ref), [
            'transfer_proof' => UploadedFile::fake()->image('comprovativo.jpg', 800, 600),
        ])->assertRedirect(route('checkout.success', $order->ref));

        $order->refresh();
        $this->assertSame('comprovativo.jpg', $order->transfer_proof_name);
        Storage::disk('local')->assertExists($order->transfer_proof_path);

        Mail::assertSent(OrderReceivedAdmin::class, function (OrderReceivedAdmin $mail) use ($order) {
            return $mail->order->is($order) && count($mail->attachments()) === 1;
        });
    }
}
