<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Mail\OrderCreated;
use App\Models\Order;
use App\Models\User;
use Auth;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Throwable;

class OrderController extends Controller
{
    public function show(): Factory|View|Application
    {
        return view('checkout.index', ['user' => Auth::user()]);
    }

    public function store(StoreOrderRequest $request): Redirector|Application|RedirectResponse
    {
        $data = $request->validated();
        try {
            $order = Order::storeNewOrder($request, $data);
        } catch (Throwable $e) {
            return back()->withErrors(['' => $e->getMessage()]);
        }

        \Mail::to($order->customer_email)->queue(new OrderCreated($order));

        return redirect(route('profile.show'));
    }

    public function index(): Factory|View|Application
    {
        $orders = Auth::user()?->orders()->orderByDesc('created_at')->paginate(5);
        return view('profile.orders', ['orders' => $orders]);
    }
}
