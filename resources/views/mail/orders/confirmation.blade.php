<x-mail::message>
# Obrigado pela sua encomenda, {{ \Illuminate\Support\Str::of($order->name)->before(' ') }}!

@if ($order->transfer_proof_path)
Recebemos a sua encomenda **{{ $order->ref }}** e o comprovativo de transferência.
A nossa equipa vai validar o pagamento e contactá-lo se for necessário.
@else
Recebemos a sua encomenda **{{ $order->ref }}**.
Faça de imediato a transferência bancária e envie o comprovativo na página da encomenda.
@endif

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

## Pagamento (transferência bancária)

**Método:** {{ $order->paymentLabel() }}
**Valor:** {{ $order->money($order->total) }}
**Descritivo / referência:** {{ $order->ref }}

@php
    $bank = $bank ?? \App\Http\Controllers\CheckoutController::bankDetails();
@endphp
@if ($bank['holder'] || $bank['iban'] || ($bank['nib'] ?? ''))
Dados da conta:
@if ($bank['holder'])
- **Titular:** {{ $bank['holder'] }}
@endif
@if ($bank['iban'])
- **IBAN:** {{ $bank['iban'] }}
@endif
@if ($bank['nib'] ?? '')
- **NIB:** {{ $bank['nib'] }}
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
@endif

@if ($order->transfer_proof_path)
Recebemos o seu comprovativo de pagamento (**{{ $order->transfer_proof_name }}**). O ficheiro segue em anexo neste email (recibo da transação).
A nossa equipa vai validar a transferência e processar a encomenda — não é necessário efetuar um novo pagamento.
@else
Faça de imediato a transferência bancária no valor de **{{ $order->money($order->total) }}** com o descritivo **{{ $order->ref }}**.
Depois envie o comprovativo na página da encomenda. Assim que confirmarmos o pagamento, a encomenda avança para preparação.
@endif

@php
    $itemsSummary = $order->items->map(fn ($it) => $it->qty.'x '.$it->name.($it->variation ? ' ('.$it->variation.')' : ''))->implode(', ');
    $whatsappMessage = "Olá! Sobre a minha encomenda {$order->ref} ({$order->money($order->total)}).\n"
        ."Nome: {$order->name}\n"
        ."Artigos: {$itemsSummary}";
@endphp

<x-mail::button :url="'https://api.whatsapp.com/send?phone=351912026453&text='.urlencode($whatsappMessage)" color="success">
Falar connosco no WhatsApp
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
