<?php

namespace App\Http\Resources;

use App\Enums\DeliveryType;
use App\Enums\PaymentMethod;
use App\Models\Order;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

/**
 * @mixin Order
 */
class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request)
    {
        $result = [
            'customer_name' => $this->customer_name,
            'customer_email' => $this->customer_email,
            'payment_method' => PaymentMethod::keyByValue($this->payment_method),
            'delivery_type' => DeliveryType::keyByValue($this->delivery_type),
            'delivery_date' => $this->created_at,
            'cart' => CartResource::make($this->cart),
        ];
        $address = $this->address;
        if ($address !== null) {
            $result += [
                'address' => AddressResource::make($address)
            ];
        }
        return $result;
    }
}
