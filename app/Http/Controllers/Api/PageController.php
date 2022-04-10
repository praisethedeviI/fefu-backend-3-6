<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PageResource;
use App\Models\Page;
use App\OpenApi\Responses\ListPageResponse;
use App\OpenApi\Responses\NotFoundResponse;
use App\OpenApi\Responses\ShowPageResponse;
use Illuminate\Contracts\Support\Responsable;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

#[OpenApi\PathItem]
class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Responsable
     */
    #[OpenApi\Operation]
    #[OpenApi\Response(factory: ListPageResponse::class, statusCode: 200)]
    public function index(): Responsable
    {
        return PageResource::collection(
            Page::query()->paginate(10)
        );
    }

    /**
     * Display the specified resource.
     *
     * @param string $slug
     * @return Responsable|PageResource
     */
    #[OpenApi\Operation]
    #[OpenApi\Response(factory: ShowPageResponse::class, statusCode: 200)]
    #[OpenApi\Response(factory: NotFoundResponse::class, statusCode: 404)]
    public function show(string $slug): Responsable|PageResource
    {
        return new PageResource(
            Page::query()->where('slug', $slug)->firstOrFail()
        );
    }
}
