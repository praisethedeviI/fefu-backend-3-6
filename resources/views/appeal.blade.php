<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Обращение</title>
</head>
<body>
<h1>Новое обращение</h1>
@if ($success)
    <p>
        Appeal sent successfully
    </p>
@endif
<form method="POST" action="{{route('appeal.send')}}">
    @csrf
    <div>
        <label>Name</label>
        <input type="text" name="name" value="{{old('name')}}" maxlength="100"/>
        @error('name')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
    <div>
        <label>Phone</label>
        <input type="text" name="phone" value="{{old('phone')}}" maxlength="16"/>
        @error('phone')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
    <div>
        <label>Email</label>
        <input type="text" name="email" value="{{old('email')}}" maxlength="100"/>
        @error('email')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
    <div>
        <label>Message</label>
        <textarea name="message" value="{{request()->isMethod('post') ? old('message') : ''}}"
                  maxlength="2000"></textarea>
        @error('message')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>
    <div>
        <input type="submit"/>
    </div>
</form>
</body>
</html>

