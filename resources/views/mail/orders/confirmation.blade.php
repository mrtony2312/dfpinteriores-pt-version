<x-mail::message>
# Obrigado pela sua encomenda, {{ \Illuminate\Support\Str::of($order->name)->before(' ') }}!

Recebemos a sua encomenda **{{ $order->ref }}** e o comprovativo de transferência.
A nossa equipa vai validar o pagamento e contactá-lo se for necessário.

<x-mail::panel>
**Encomenda {{ $order->ref }}** &nbsp;·&nbsp; {{ $order->created_at->format('d/m/Y H:i') }}
@if ($order->transfer_proof_name)
<br>Comprovativo recebido: **{{ $order->transfer_proof_name }}**
@endif
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

**Método:** {{ $order->paymentLabel() }}
**Valor:** {{ $order->money($order->total) }}

@if (! empty($bank['iban']) || ! empty($bank['holder']))
Dados utilizados para a transferência:

| | |
| :-- | :-- |
@if (! empty($bank['holder']))
| Titular | {{ $bank['holder'] }} |
@endif
@if (! empty($bank['bank_name']))
| Banco | {{ $bank['bank_name'] }} |
@endif
@if (! empty($bank['iban']))
| IBAN | {{ $bank['iban'] }} |
@endif
@if (! empty($bank['bic']))
| BIC/SWIFT | {{ $bank['bic'] }} |
@endif
@if (! empty($bank['nif']))
| NIF | {{ $bank['nif'] }} |
@endif
@endif

Indique a referência **{{ $order->ref }}** no descritivo da transferência.

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
