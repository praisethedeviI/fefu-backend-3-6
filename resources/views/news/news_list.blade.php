<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>News List</title>
    <style>
        div span a svg, div span span svg {
            width: 10%;
            height: auto;
        }
    </style>
</head>
<body>
@foreach($news_list as $news)
    <a href="{{ route('news.show', $news->slug) }}">
        <h1>{{ $news->title }}</h1>
    </a>*
    <p>{{ $news->text }}</p>
    <p>{{ $news->published_at }}</p>
@endforeach
{{ $news_list->links() }}
</body>
</html>
