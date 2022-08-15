<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thanks for appeal</title>
</head>
<body>
<h1>New appeal №{{ $appeal->id }}</h1>
<div>
    <h2>From {{ $appeal->name }}</h2>
    <p>Message: {{ $appeal->message }}</p>
</div>
</body>
</html>
