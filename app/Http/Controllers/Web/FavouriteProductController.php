<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\ListProductResource;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class FavouriteProductController extends Controller
{
    public function show(): Factory|View|Application
    {
        /** @var User $user */
        $user = Auth::user();
        $products = $user->favouriteProducts()
            ->orderBy('created_at', 'desc')
            ->paginate(5);
        return view('profile.favourite_products', ['products' => ListProductResource::collection($products)]);
    }
}
