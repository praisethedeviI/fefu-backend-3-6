<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NewsResource;
use App\Models\News;
use App\OpenApi\Responses\ListNewsResponse;
use App\OpenApi\Responses\NotFoundResponse;
use App\OpenApi\Responses\ShowNewsResponse;
use Illuminate\Contracts\Support\Responsable;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

#[OpenApi\PathItem]
class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Responsable
     */
    #[OpenApi\Operation]
    #[OpenApi\Response(factory: ListNewsResponse::class, statusCode: 200)]
    public function index()
    {
        return NewsResource::collection(
            News::query()->published()->paginate(5)
        );
    }

    /**
     * Display the specified resource.
     *
     * @param string $slug
     * @return NewsResource|Responsable
     */
    #[OpenApi\Operation]
    #[OpenApi\Response(factory: ShowNewsResponse::class, statusCode: 200)]
    #[OpenApi\Response(factory: NotFoundResponse::class, statusCode: 404)]
    public function show(string $slug): NewsResource|Responsable
    {
        return new NewsResource(
            News::query()->published()->where('slug', $slug)->firstOrFail()
        );
    }
}
