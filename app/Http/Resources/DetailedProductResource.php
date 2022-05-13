<?php

namespace App\Http\Resources;

use App\Models\Product;
use App\Models\ProductAttributeValue;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Product
 */
class DetailedProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'price' => $this->price,
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
