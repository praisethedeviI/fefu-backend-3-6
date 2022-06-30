<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile</title>
</head>
<body>
<h1>Profile</h1>
<div>
    <a href="{{ route('profile.favourite') }}" class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline">Favourite</a>
    <a href="{{ route('profile.orders') }}" class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline">Orders</a>
    <form action="{{route('profile.update')}}" method="POST">
        @csrf
        <div>
            <label>Имя</label>
            <input type="text" name="name" value="{{old('name') ?? $user['name']}}"  maxlength="255" />
            @error('name')
            <div>{{$message}}</div>
            @enderror
        </div>
        <div>
            <label>Email</label>
            <input type="text" name="email" value="{{old('email') ?? $user['email']}}"  maxlength="255" />
            @error('email')
            <div>{{$message}}</div>
            @enderror
        </div>
        <div>
            <input type="submit"/>
        </div>
    </form>

    <h4>App:</h4>
    <label>
        <b> Last login date:</b> {{ $user['app_logged_in_at'] ?? 'Never' }}
        <b> Registration date:</b> {{ $user['app_registered_at'] ?? 'Never' }}
    </label>
    <h3>OAuth info:</h3>
    <h4>Github:</h4>
    <label>
        <b> Last login date:</b> {{ $user['github_logged_in_at'] ?? 'Never' }}
        <b> Registration date:</b> {{ $user['github_registered_at'] ?? 'Never' }}
    </label>
    <h4>Discord:</h4>
    <label>
        <b> Last login date:</b> {{ $user['discord_logged_in_at'] ?? 'Never' }}
        <b> Registration date:</b> {{ $user['discord_registered_at'] ?? 'Never' }}
    </label>
</div>
<p>{{ $user['name'] }}</p>
<form action="{{ route('logout') }}" method="post">
    @csrf
    <button type="submit">Logout</button>
</form>
</body>
</html>
