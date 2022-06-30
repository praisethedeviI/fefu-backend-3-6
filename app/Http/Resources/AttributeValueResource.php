<?php

namespace App\Http\Resources;

use App\Models\ProductAttributeValue;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

/**
 * @mixin ProductAttributeValue
 */
class AttributeValueResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'name' => $this->productAttribute->name,
            'value' => $this->value,
        ];
    }
}
