<?php

namespace App\Http\Requests;

use App\Enums\DeliveryType;
use App\Enums\PaymentMethod;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'customer_email' => ['required', 'email:rfc'],
            'customer_name' => ['required', 'min:3', 'max:255'],
            'delivery_type' => ['required', Rule::in(['courier', 'pickup'])],
            'payment_method' => ['required', Rule::in(['cash', 'card'])],
            'delivery_address.city' => ['required_if:delivery_type,courier'],
            'delivery_address.street' => ['required_if:delivery_type,courier'],
            'delivery_address.house' => ['required_if:delivery_type,courier'],
            'delivery_address.apartment' => ['integer', 'nullable'],
        ];
    }
}
