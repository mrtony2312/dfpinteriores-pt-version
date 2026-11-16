@extends('layouts.app')

@section('title', 'Encomenda confirmada | DFP Interiores')

@section('content')
<div class="OrderDone">
    <div class="tick">✓</div>
    <h1>Obrigado, {{ \Illuminate\Support\Str::of($order->name)->before(' ') }}!</h1>
    <p>A sua encomenda <strong>{{ $order->ref }}</strong> foi registada. Enviámos a confirmação para {{ $order->email }}.</p>

    <div class="ref-box">
        <div class="row"><span>Método</span><span>{{ $payment[$order->payment_method]['label'] ?? 'Transferência bancária' }}</span></div>
        @if ($bank['holder'])
            <div class="row"><span>Titular</span><span>{{ $bank['holder'] }}</span></div>
        @endif
        @if ($bank['bank_name'])
            <div class="row"><span>Banco</span><span>{{ $bank['bank_name'] }}</span></div>
        @endif
        @if ($bank['iban'])
            <div class="row"><span>IBAN</span><span><strong>{{ $bank['iban'] }}</strong></span></div>
        @endif
        @if ($bank['bic'])
            <div class="row"><span>BIC/SWIFT</span><span>{{ $bank['bic'] }}</span></div>
        @endif
        @if ($bank['nif'])
            <div class="row"><span>NIF</span><span>{{ $bank['nif'] }}</span></div>
        @endif
        <div class="row"><span>Montante</span><span>{{ number_format((float) $order->total, 2, ',', ' ') }}€</span></div>
        <div class="row"><span>Descritivo</span><span><strong>{{ $order->ref }}</strong></span></div>
        @if ($order->transfer_proof_name)
            <div class="row"><span>Comprovativo</span><span>{{ $order->transfer_proof_name }}</span></div>
            <p style="font-size:12px;color:#999;margin-top:10px;">
                O comprovativo foi enviado por email com a confirmação da encomenda.
            </p>
        @else
            <p style="font-size:12px;color:#999;margin-top:10px;">
                Faça a transferência com a referência da encomenda no descritivo. Processamos assim que o pagamento for confirmado.
            </p>
        @endif
    </div>

    <div class="ref-box">
        @foreach ($order->items as $it)
            <div class="row">
                <span>{{ $it->name }}@if ($it->variation) <small style="color:#999;">({{ $it->variation }})</small>@endif &times; {{ $it->qty }}</span>
                <span>{{ number_format((float) $it->line_total, 2, ',', ' ') }}€</span>
            </div>
        @endforeach
        <div class="row"><span>Portes — {{ $shipping[$order->shipping_method]['label'] ?? $order->shipping_method }}</span><span>{{ $order->shipping_cost > 0 ? number_format((float) $order->shipping_cost, 2, ',', ' ').'€' : 'Grátis' }}</span></div>
        <div class="row big"><span>Total</span><span>{{ number_format((float) $order->total, 2, ',', ' ') }}€</span></div>
    </div>

    <div class="ref-box">
        <div class="row"><span>Entrega em</span><span>{{ $order->address }}, {{ $order->postal_code }} {{ $order->city }}</span></div>
        @if (! $order->billing_same && $order->billing_address)
            <div class="row"><span>Faturação</span><span>{{ $order->billing_name ?: $order->name }} — {{ $order->billing_address }}, {{ $order->billing_postal_code }} {{ $order->billing_city }}</span></div>
        @endif
        <div class="row"><span>Confirmação enviada para</span><span>{{ $order->email }}</span></div>
    </div>

    <a href="/" class="btn">Continuar a comprar</a>
</div>
@endsection
