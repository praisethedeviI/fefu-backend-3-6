<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Favourite</title>
    <style>
        div span a svg, div span span svg {
            width: 10%;
            height: auto;
        }
        hr {
            size: 50pt;
        }
    </style>
</head>
<body>
@foreach($orders as $order)
    <hr />
    <h3>Delivery Date: {{ $order->delivery_date }}</h3>

    <div>
        <p>Name: {{ $order->customer_name }}</p>
        <p>Email: {{ $order->customer_email }}</p>
        <p>Payment method: {{ \App\Enums\PaymentMethod::keyByValue($order->payment_method) }}</p>
        <p>Delivery type: {{ \App\Enums\DeliveryType::keyByValue($order->delivery_type) }}</p>
    </div>

    <div>
        @if($order->delivery_type === \App\Enums\DeliveryType::COURIER)
            <p>City: {{ $order->address->city }}</p>
            <p>Street: {{ $order->address->street }}</p>
            <p>House: {{ $order->address->house }}</p>
            <p>Apartments: {{ $order->address->apartment ?? "Not specified"}}</p>
        @endif
    </div>

    <div>
        <h4>Price Total: {{ $order->cart->price_total }} rub.</h4>
        @foreach($order->cart->items as $cartItem)
            <div >
                <b>Product Name: {{$cartItem->product->name}}</b>
            </div>

            <p>Quantity: {{ $cartItem->quantity }}</p>
            <p>Item price: {{ $cartItem->price_item }} rub.</p>
            <p>Price total: {{ $cartItem->price_total }} rub.</p>
        @endforeach
    </div>
@endforeach
{{ $orders->links() }}
</body>
</html>
