<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>News List</title>
</head>
<body>
@foreach($news_list as $news)
    <h1>{{ $news->title }}</h1>
    <p>{{ $news->text }}</p>
    <p>{{ $news->published_at }}</p>
@endforeach
{{ $news_list->links() }}
</body>
</html>
