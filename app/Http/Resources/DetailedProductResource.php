<?php

namespace App\Http\Resources;

use App\Models\Product;
use App\Models\ProductAttributeValue;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

/**
 * @mixin Product
 */
class DetailedProductResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'price' => $this->price,
            'is_favourite' => $this->isFavourite(),
            'description' => $this->description,
            'category' => new CategoryResource(
                $this->productCategory
            ),
            'attributes' => AttributeValueResource::collection(
                $this->sortedAttributeValues
            )
        ];
    }
}
