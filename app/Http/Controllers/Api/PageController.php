<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PageResource;
use App\Models\Page;
use Illuminate\Contracts\Support\Responsable;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Responsable
     */
    public function index()
    {
        return PageResource::collection(
            Page::query()->paginate(10)
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  string $slug
     * @return Responsable
     */
    public function show(string $slug)
    {
        return new PageResource(
            Page::query()->where('slug', $slug)->firstOrFail()
        );
    }
}
