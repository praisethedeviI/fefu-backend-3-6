<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\PageResource;
use App\Models\ProductCategory;
use App\OpenApi\Responses\CatalogResponse;
use App\OpenApi\Responses\NotFoundResponse;
use App\OpenApi\Responses\ProductCategoryResponse;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

#[OpenApi\PathItem]
class CatalogController extends Controller
{
    /**
     * Display catalog of product categories.
     *
     * @return Responsable
     */
    #[OpenApi\Operation(tags: ['catalog'], method: 'GET')]
    #[OpenApi\Response(factory: CatalogResponse::class, statusCode: 200)]
    public function index()
    {
        return CategoryResource::collection(
            ProductCategory::query()->get(),
        );
    }

    /**
     * Display the specified product category.
     *
     * @param string $slug
     * @return CategoryResource|Responsable
     */
    #[OpenApi\Operation(tags: ['catalog'], method: 'GET')]
    #[OpenApi\Response(factory: ProductCategoryResponse::class, statusCode: 200)]
    #[OpenApi\Response(factory: NotFoundResponse::class, statusCode: 404)]
    public function show(string $slug): CategoryResource|Responsable
    {
        return new CategoryResource(
            ProductCategory::query()->where('slug', $slug)->firstOrFail()
        );
    }
}
