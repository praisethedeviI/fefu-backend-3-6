<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ListProductCollection;
use App\Http\Resources\ListProductResource;
use App\Models\FavouriteProduct;
use App\Models\Product;
use App\Models\User;
use App\OpenApi\Parameters\ToggleFavouriteProductParameters;
use App\OpenApi\Responses\Auth\UnauthenticatedResponse;
use App\OpenApi\Responses\FavouriteProductResponse;
use App\OpenApi\SecuritySchemes\BearerTokenSecurityScheme;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HigherOrderTapProxy;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

#[OpenApi\PathItem]
class FavouriteProductController extends Controller
{
    /**
     * Return all favourite products with pagination.
     *
     * @return ListProductCollection|AnonymousResourceCollection|HigherOrderTapProxy|mixed
     */
    #[OpenApi\Operation(tags: ['profile', 'favourite_product'], security: BearerTokenSecurityScheme::class, method: 'GET')]
    #[OpenApi\Response(factory: FavouriteProductResponse::class, statusCode: 200)]
    #[OpenApi\Response(factory: UnauthenticatedResponse::class, statusCode: 401)]
    public function show(): mixed
    {
        /** @var User $user */
        $user = Auth::user();
        $products = $user->favouriteProducts()
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return ListProductResource::collection($products);
    }

    /**
     * Toggle favourite product by slug or id of product.
     *
     * @param Request $request
     * @return ListProductCollection|AnonymousResourceCollection|HigherOrderTapProxy|mixed
     */
    #[OpenApi\Operation(tags: ['profile', 'favourite_product'], security: BearerTokenSecurityScheme::class, method: 'POST')]
    #[OpenApi\Response(factory: FavouriteProductResponse::class, statusCode: 200)]
    #[OpenApi\Response(factory: UnauthenticatedResponse::class, statusCode: 401)]
    #[OpenApi\Parameters(factory: ToggleFavouriteProductParameters::class)]
    public function update(Request $request): mixed
    {
        $validated = $request->validate([
            'slug' => 'required_unless:id,null|string',
            'id' => 'int',

        ]);
        /** @var User $user */
        $user = Auth::user();
        $productQuery = Product::query();
        $id = $validated['id'] ?? null;
        if ($id === null) {
            $productQuery->where('slug', $validated['slug'] ?? null);
        } else {
            $productQuery->where('id', $id ?? null);
        }
        /** @var Product $product */
        $product = $productQuery->firstOrFail();
        if ($product->isFavourite()) {
            FavouriteProduct::query()->where('product_id', $product->id)->delete();
        } else {
            $now = Carbon::now();
            $favouriteProduct = new FavouriteProduct();
            $favouriteProduct->created_at = $now;
            $favouriteProduct->updated_at = $now;
            $favouriteProduct->product_id = $product->id;
            $favouriteProduct->user_id = $user->id;
            $favouriteProduct->save();
        }
        $product->isFavourite();
        return ListProductResource::collection($user->favouriteProducts()
            ->orderBy('created_at', 'desc')
            ->paginate(5));
    }
}
