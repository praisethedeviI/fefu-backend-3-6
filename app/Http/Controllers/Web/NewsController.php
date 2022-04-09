<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use function abort;
use function view;

class NewsController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, string $slug)
    {
        $news = News::query()
            ->published()
            ->where('slug', $slug)
            ->first();
        if ($news === null)
        {
            abort(404);
        }
        return view('news', ['news' => $news]);
    }
}
