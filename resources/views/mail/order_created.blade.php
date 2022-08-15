<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thanks for order</title>
</head>
<body>
<h1>Thanks for order №{{ $order->id }}</h1>
<div>
    <h2>Items:</h2>
    <ul>
        @foreach($order->items as $item)
            <li>{{ $item->product->name }} -- {{ $item->price_item }} x {{$item->quantity}} = {{ $item->price_total }}</li>
        @endforeach
    </ul>
</div>


</body>
</html>
