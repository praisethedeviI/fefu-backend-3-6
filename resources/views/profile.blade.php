<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile</title>
</head>
<body>
<h1>Profile</h1>
<p>{{ $user->name }}</p>
<form action="{{ route('logout') }}" method="post">
    @csrf
    <button type="submit">Logout</button>
</form>
</body>
</html>
