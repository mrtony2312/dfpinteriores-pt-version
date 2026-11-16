<x-mail::message>
# Obrigado pela sua encomenda, {{ \Illuminate\Support\Str::of($order->name)->before(' ') }}!

Recebemos a sua encomenda **{{ $order->ref }}** e está a ser processada.
Vai receber uma nova mensagem assim que for expedida.

<x-mail::panel>
**Encomenda {{ $order->ref }}** &nbsp;·&nbsp; {{ $order->created_at->format('d/m/Y H:i') }}
</x-mail::panel>

## Resumo

<x-mail::table>
| Artigo | Qt. | Total |
| :----- | :-: | ----: |
@foreach ($order->items as $it)
| {{ $it->name }}{{ $it->variation ? ' ('.$it->variation.')' : '' }} | {{ $it->qty }} | {{ $order->money($it->line_total) }} |
@endforeach
| **Subtotal** | | {{ $order->money($order->subtotal) }} |
| **Portes** ({{ $order->shippingLabel() }}) | | {{ $order->shipping_cost > 0 ? $order->money($order->shipping_cost) : 'Grátis' }} |
| **Total** | | **{{ $order->money($order->total) }}** |
</x-mail::table>

## Pagamento

Para confirmar a sua encomenda **{{ $order->ref }}**, falta apenas concluir o pagamento no valor de **{{ $order->money($order->total) }}**.

**Método:** {{ $order->paymentLabel() }}

@php($bank = \App\Http\Controllers\CheckoutController::bankDetails())
Efetue a transferência bancária no valor de **{{ $order->money($order->total) }}** com o descritivo **{{ $order->ref }}**:

@if ($bank['holder'] || $bank['iban'])
@if ($bank['holder'])
- **Titular:** {{ $bank['holder'] }}
@endif
@if ($bank['iban'])
- **IBAN:** {{ $bank['iban'] }}
@endif
@if ($bank['bic'])
- **BIC/SWIFT:** {{ $bank['bic'] }}
@endif
@if ($bank['bank_name'])
- **Banco:** {{ $bank['bank_name'] }}
@endif
@if ($bank['nif'])
- **NIF:** {{ $bank['nif'] }}
@endif
@else
Os dados bancários serão comunicados pela nossa equipa.
@endif

@if ($order->transfer_proof_path)
Recebemos o seu comprovativo (**{{ $order->transfer_proof_name }}**) — também segue em anexo neste email.
@endif

A encomenda será processada após confirmação do pagamento.

Prefere falar connosco? Toque no botão abaixo para contactar a nossa equipa pelo WhatsApp.

@php
    $itemsSummary = $order->items->map(fn ($it) => $it->qty.'x '.$it->name.($it->variation ? ' ('.$it->variation.')' : ''))->implode(', ');
    $whatsappMessage = "Olá! Quero finalizar o pagamento da minha encomenda {$order->ref}, no valor de {$order->money($order->total)}.\n"
        ."Nome: {$order->name}\n"
        ."Artigos: {$itemsSummary}";
@endphp

<x-mail::button :url="'https://api.whatsapp.com/send?phone=351912026453&text='.urlencode($whatsappMessage)" color="success">
Finalizar a minha encomenda no WhatsApp
</x-mail::button>

## Entrega

{{ $order->name }}
{{ $order->address }}
{{ $order->postal_code }} {{ $order->city }}
{{ $order->phone }}

@if (! $order->billing_same && $order->billing_address)
## Faturação

{{ $order->billing_name ?: $order->name }}
{{ $order->billing_address }}
{{ $order->billing_postal_code }} {{ $order->billing_city }}
@endif

<x-mail::button :url="url('/carrinho/checkout/sucesso/'.$order->ref)">
Ver a minha encomenda
</x-mail::button>

Qualquer questão? Responda a este email ou contacte o nosso apoio ao cliente.

Obrigado,<br>
Equipa **DFP Interiores**

<x-mail::subcopy>
Este email foi enviado para {{ $order->email }} na sequência da encomenda {{ $order->ref }}.
</x-mail::subcopy>
</x-mail::message>
