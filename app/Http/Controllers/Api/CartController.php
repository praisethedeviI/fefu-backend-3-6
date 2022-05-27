<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CartModificationRequest;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\Product;
use App\OpenApi\Parameters\CartModificationParameters;
use App\OpenApi\RequestBodies\CartModificationRequestBody;
use App\OpenApi\Responses\Cart\ShowCartResponse;
use App\OpenApi\SecuritySchemes\BearerTokenSecurityScheme;
use Illuminate\Support\Facades\Auth;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

#[OpenApi\PathItem]
class CartController extends Controller
{
    /**
     * Add products to cart and set quantity
     *
     * @param CartModificationRequest $request
     * @return CartResource
     */
    #[OpenApi\Operation(tags: ['cart'], security: BearerTokenSecurityScheme::class, method: 'POST')]
    #[OpenApi\Response(factory: ShowCartResponse::class, statusCode: 200)]
    #[OpenApi\RequestBody(factory: CartModificationRequestBody::class)]
    public function setQuantity(CartModificationRequest $request): CartResource
    {
        $data = $request->validated('modifications');
        $user = Auth::user();

        $sessionId = session()->getId();
        $cart = Cart::getOrCreateCart($user, $sessionId);

        $productIds = array_column($data, 'product_id');
        $productsById = Product::whereIn('id', $productIds)->get()->keyBy('id');
        foreach ($data as $modification) {
            $cart->setProductQuantity($productsById[$modification['product_id']], $modification['quantity']);
        }
        $cart->recalculateCart();
        $cart->save();

        return CartResource::make($cart);
    }

    /**
     * Return cart by user or session
     *
     * @return CartResource
     */
    #[OpenApi\Operation(tags: ['cart'], security: BearerTokenSecurityScheme::class, method: 'GET')]
    #[OpenApi\Response(factory: ShowCartResponse::class, statusCode: 200)]
    public function show(): CartResource
    {
        return CartResource::make(Cart::getOrCreateCart(Auth::user(), session()->getId()));
    }
}
