<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Mail\OrderCreated;
use App\Models\Order;
use App\OpenApi\Parameters\OrderParameters;
use App\OpenApi\RequestBodies\OrderRequestBody;
use App\OpenApi\Responses\Auth\UnauthenticatedResponse;
use App\OpenApi\Responses\CreateOrderResponse;
use App\OpenApi\Responses\UserOrdersResponse;
use App\OpenApi\SecuritySchemes\BearerTokenSecurityScheme;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Mail;
use Request;
use Throwable;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;

#[OpenApi\PathItem]
class OrderController extends Controller
{
    /**
     * Create and return an order.
     *
     * @param StoreOrderRequest $request
     * @return OrderResource|JsonResponse
     */
    #[OpenApi\Operation(tags: ['order'], security: BearerTokenSecurityScheme::class, method: 'POST')]
    #[OpenApi\RequestBody(factory: OrderRequestBody::class)]
    #[OpenApi\Parameters(factory: OrderParameters::class)]
    #[OpenApi\Response(factory: CreateOrderResponse::class, statusCode: 200)]
    public function store(StoreOrderRequest $request): JsonResponse|OrderResource
    {
        $data = $request->validated();
        try {
            $order = Order::storeNewOrder($request, $data);
        } catch (Throwable $e) {
            return response()->json(['errors' => ['' => $e->getMessage()]], 422);
        }

        Mail::to($order->customer_email)->queue(new OrderCreated($order));

        return OrderResource::make($order);
    }

    /**
     * Show all user orders with pagination.
     *
     * @return AnonymousResourceCollection
     */
    #[OpenApi\Operation(tags: ['profile', 'order'], security: BearerTokenSecurityScheme::class, method: 'GET')]
    #[OpenApi\Response(factory: UserOrdersResponse::class, statusCode: 200)]
    #[OpenApi\Response(factory: UnauthenticatedResponse::class, statusCode: 401)]
    public function show(): AnonymousResourceCollection
    {
        $orders = Auth::user()?->orders()->orderByDesc('created_at')->paginate(5);
        return OrderResource::collection($orders);
    }
}
