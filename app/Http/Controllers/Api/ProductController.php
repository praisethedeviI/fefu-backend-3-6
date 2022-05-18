<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ListProductResource;
use App\Http\Resources\DetailedProductResource;
use App\Models\Product;
use App\Models\ProductCategory;
use App\OpenApi\Parameters\Product\DetailParameters;
use App\OpenApi\Parameters\Product\ListParameters;
use App\OpenApi\Responses\NotFoundResponse;
use App\OpenApi\Responses\Product\DetailProductResponse;
use App\OpenApi\Responses\Product\ListProductResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Throwable;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

#[OpenApi\PathItem]
class ProductController extends Controller
{
    /**
     * Display a paginated list of category products.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    #[OpenApi\Operation(tags: ['product'], method: 'GET')]
    #[OpenApi\Response(factory: ListProductResponse::class, statusCode: 200)]
    #[OpenApi\Response(factory: NotFoundResponse::class, statusCode: 404)]
    #[OpenApi\Parameters(factory: ListParameters::class)]
    public function index(Request $request): AnonymousResourceCollection
    {
        $slug = $request->query('category_slug');
        $categoryQuery = ProductCategory::query()
            ->with('children', 'products');

        if ($slug === null) {
            $categoryQuery->where('parent_id');
        } else {
            $categoryQuery->where('slug', $slug);
        }

        $categories = $categoryQuery->get();
        /** @var Product $products */
        try {
            $products = ProductCategory::getTreeProductBuilder($categories)
                ->orderBy('id')
                ->paginate();
        } catch (Throwable $e) {
            abort(422, $e->getMessage());
        }


        return ListProductResource::collection(
            $products
        );
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
