<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Вход</title>
</head>
<body>
<h1>Вход</h1>
<form method="POST" action="{{ route('login.post') }}">
    @csrf
    <div>
        <label>Email</label>
        <input type="text" name="email" value="{{old('email')}}" maxlength="100"/>
        @error('email')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
    <div>
        <label>Password</label>
        <input name="password" value="{{ old('password') }}"
                  maxlength="2000"/>
        @error('password')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
    <div>
        <input type="submit"/>
    </div>
</form>
<a href="{{ route('oauth.redirect', ['provider' => 'discord']) }}">Login through Discord</a>
<br>
<a href="{{ route('oauth.redirect', ['provider' => 'github']) }}">Login through GitHub</a>
</body>
</html>

