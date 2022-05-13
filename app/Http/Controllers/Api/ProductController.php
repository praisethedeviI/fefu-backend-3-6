<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CatalogFormRequest;
use App\Http\Resources\ListProductResource;
use App\Http\Resources\DetailedProductResource;
use App\Http\Resources\ListProductCollection;
use App\Models\Product;
use App\OpenApi\Parameters\Product\DetailParameters;
use App\OpenApi\Parameters\Product\ListParameters;
use App\OpenApi\Responses\NotFoundResponse;
use App\OpenApi\Responses\Product\DetailProductResponse;
use App\OpenApi\Responses\Product\ListProductResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\HigherOrderTapProxy;
use Throwable;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

#[OpenApi\PathItem]
class ProductController extends Controller
{
    /**
     * Display a paginated list of category products.
     *
     * @param CatalogFormRequest $request
     * @return ListProductCollection|AnonymousResourceCollection|HigherOrderTapProxy|mixed
     */
    #[OpenApi\Operation(tags: ['product'], method: 'GET')]
    #[OpenApi\Response(factory: ListProductResponse::class, statusCode: 200)]
    #[OpenApi\Response(factory: NotFoundResponse::class, statusCode: 404)]
    #[OpenApi\Parameters(factory: ListParameters::class)]
    public function index(CatalogFormRequest $request): mixed
    {
        $requestData = $request->validated();


        $requestData['slug'] = $requestData['category_slug'] ?? null;
        try {
            $data = Product::findProducts($requestData);
        } catch (Throwable $e) {
            abort(422, $e->getMessage());
        }

        return ListProductResource::collection(
            $data['product_query']->orderBy('products.id')->paginate()->appends([
                'category_slug' => $data['key_params']['category_slug'],
                'search_query' => $data['key_params']['search_query'],
                'filters' => $data['key_params']['filters'],
                'sort_mode' => $data['key_params']['sort_mode']
            ])
        )->additional($data['filters']);
    }

    /**
     * Display the specified product with attributes and description.
     *
     * @param Request $request
     * @return DetailedProductResource
     */
    #[OpenApi\Operation(tags: ['product'], method: 'GET')]
    #[OpenApi\Response(factory: DetailProductResponse::class, statusCode: 200)]
    #[OpenApi\Response(factory: NotFoundResponse::class, statusCode: 404)]
    #[OpenApi\Parameters(factory: DetailParameters::class)]
    public function show(Request $request): DetailedProductResource
    {
        $slug = $request->query('product_slug');
        $product = Product::query()
            ->with('productCategory', 'sortedAttributeValues.productAttribute')
            ->where('slug', $slug)
            ->firstOrFail();
        return new DetailedProductResource(
            $product
        );
    }
}
