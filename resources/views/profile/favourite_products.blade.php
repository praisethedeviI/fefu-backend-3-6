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
    </style>
</head>
<body>
@foreach($products as $product)
    <a href="{{ route('product', $product->slug) }}">
        <h3>{{ $product->name }}</h3>
    </a>
    <p>{{ $product->price }}</p>
@endforeach
{{ $products->links() }}
</body>
</html>
