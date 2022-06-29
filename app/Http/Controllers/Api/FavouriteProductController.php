<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DetailedProductResource;
use App\Http\Resources\ListProductCollection;
use App\Http\Resources\ListProductResource;
use App\Models\FavouriteProduct;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HigherOrderTapProxy;

class FavouriteProductController extends Controller
{
    /**
     * @return ListProductCollection|AnonymousResourceCollection|HigherOrderTapProxy|mixed
     */
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
     * @param Request $request
     * @return ListProductCollection|AnonymousResourceCollection|HigherOrderTapProxy|mixed
     */
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
