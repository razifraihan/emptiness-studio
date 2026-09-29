@extends('emails.layout')

@section('title', 'Order ' . $order->code)

@section('content')
<h1 style="margin:0;font-size:24px;font-weight:600;line-height:1.3;">Thank you for your order.</h1>

<p style="margin:16px 0 0;font-size:15px;line-height:1.7;color:#525252;">
    We've received order <strong style="color:#171717;">{{ $order->code }}</strong>.
    Here is a quiet summary of what you chose.
</p>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-top:32px;border-top:1px solid #e5e5e5;">
    @foreach ($order->items as $item)
        <tr>
            <td style="padding:16px 0;border-bottom:1px solid #e5e5e5;font-size:14px;line-height:1.6;">
                {{ $item->product_name }}
                @if ($item->variant_label)
                    <br><span style="color:#737373;">{{ $item->variant_label }}</span>
                @endif
                <br><span style="color:#737373;">Qty {{ $item->quantity }}</span>
            </td>
            <td align="right" valign="top" style="padding:16px 0;border-bottom:1px solid #e5e5e5;font-size:14px;white-space:nowrap;">
                Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
            </td>
        </tr>
    @endforeach
</table>

<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-top:16px;font-size:14px;line-height:2;">
    <tr>
        <td style="color:#737373;">Subtotal</td>
        <td align="right">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
    </tr>
    @if ($order->shipping_cost > 0)
        <tr>
            <td style="color:#737373;">Shipping</td>
            <td align="right">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
        </tr>
    @endif
    @if ($order->discount > 0)
        <tr>
            <td style="color:#737373;">Discount</td>
            <td align="right">-Rp {{ number_format($order->discount, 0, ',', '.') }}</td>
        </tr>
    @endif
    <tr>
        <td style="font-weight:600;">Total</td>
        <td align="right" style="font-weight:600;">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
    </tr>
</table>

<p style="margin:40px 0 0;font-size:12px;letter-spacing:1.5px;text-transform:uppercase;color:#737373;">Shipping to</p>
<p style="margin:8px 0 0;font-size:14px;line-height:1.7;color:#404040;">
    {{ $order->customer_name }}<br>
    {!! nl2br(e($order->address)) !!}<br>
    {{ $order->district }}, {{ $order->city }}<br>
    {{ $order->province }} {{ $order->postal_code }}
</p>

@if ($order->status === 'pending' && $order->expires_at)
    <p style="margin:32px 0 0;font-size:14px;line-height:1.7;color:#525252;">
        Your order is waiting for payment. We're holding your items until
        {{ $order->expires_at->format('j F Y, H:i') }} WIB.
    </p>
@endif

@if ($order->status === 'pending')
    <p style="margin:40px 0 0;font-size:12px;letter-spacing:1.5px;text-transform:uppercase;color:#737373;">How to pay</p>
    @if (config('store.bank_account_number'))
        <p style="margin:8px 0 0;font-size:14px;line-height:1.7;color:#404040;">
            We'll message you on WhatsApp to confirm the shipping cost. Once confirmed, please transfer the final total to:
        </p>
        <p style="margin:12px 0 0;font-size:14px;line-height:1.7;color:#171717;">
            {{ config('store.bank_name') }}<br>
            {{ config('store.bank_account_number') }}<br>
            Account name: {{ config('store.bank_account_name') }}
        </p>
        <p style="margin:12px 0 0;font-size:14px;line-height:1.7;color:#404040;">
            Then send your proof of transfer, with order number <strong>{{ $order->code }}</strong>, to {{ config('store.proof_contact') }}.
        </p>
    @else
        <p style="margin:8px 0 0;font-size:14px;line-height:1.7;color:#404040;">
            We'll message you on WhatsApp to confirm the shipping cost and share the payment details.
        </p>
    @endif
@endif

<p style="margin:32px 0 0;">
    <a href="{{ $orderUrl }}"
       style="display:inline-block;padding:12px 24px;border:1px solid #171717;color:#171717;font-size:14px;text-decoration:none;">
        View your order
    </a>
</p>
@endsection