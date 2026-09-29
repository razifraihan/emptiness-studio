@extends('emails.layout')

@section('title', 'Order ' . $order->code)

@section('content')
<h1 style="margin:0;font-size:24px;font-weight:600;line-height:1.3;">Your order is on its way.</h1>

<p style="margin:16px 0 0;font-size:15px;line-height:1.7;color:#525252;">
    Order <strong style="color:#171717;">{{ $order->code }}</strong> has left our hands.
    Packages take their own time. Thank you for your patience.
</p>

<p style="margin:32px 0 0;font-size:12px;letter-spacing:1.5px;text-transform:uppercase;color:#737373;">Delivery</p>
<p style="margin:8px 0 0;font-size:14px;line-height:1.8;color:#404040;">
    @if ($order->courier)
        Courier: {{ $order->courier }}@if ($order->courier_service) ({{ $order->courier_service }})@endif<br>
    @endif
    @if ($order->tracking_number)
        Tracking number: <strong style="color:#171717;">{{ $order->tracking_number }}</strong>
    @else
        Tracking details will follow.
    @endif
</p>

<p style="margin:32px 0 0;font-size:12px;letter-spacing:1.5px;text-transform:uppercase;color:#737373;">Shipping to</p>
<p style="margin:8px 0 0;font-size:14px;line-height:1.7;color:#404040;">
    {{ $order->customer_name }}<br>
    {!! nl2br(e($order->address)) !!}<br>
    {{ $order->district }}, {{ $order->city }}<br>
    {{ $order->province }} {{ $order->postal_code }}
</p>

<p style="margin:32px 0 0;font-size:14px;line-height:1.8;color:#525252;">
    @foreach ($order->items as $item)
        {{ $item->product_name }}@if ($item->variant_label) ({{ $item->variant_label }})@endif × {{ $item->quantity }}<br>
    @endforeach
</p>

<p style="margin:32px 0 0;">
    <a href="{{ $orderUrl }}"
       style="display:inline-block;padding:12px 24px;border:1px solid #171717;color:#171717;font-size:14px;text-decoration:none;">
        View your order
    </a>
</p>
@endsection