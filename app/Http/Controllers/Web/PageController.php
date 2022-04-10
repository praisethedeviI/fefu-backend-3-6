<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use function abort;
use function view;

class PageController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param Request $request
     * @param string $slug
     * @return Application|Factory|View
     */
    public function __invoke(Request $request, string $slug): View|Factory|Application
    {
        $page = Page::query()
            ->where('slug', $slug)
            ->first();
        if ($page === null)
        {
            abort(404);
        }
        return view('page', ['page' => $page]);
    }
}
