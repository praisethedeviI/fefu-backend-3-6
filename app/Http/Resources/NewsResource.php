<?php

namespace App\Http\Resources;

use App\Models\News;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

/**
 * @mixin News
 */
class NewsResource extends JsonResource
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
            'title' => $this->title,
            'text' => $this->text,
            'published_at' => $this->published_at,
            'slug' => $this->slug
        ];
    }
}
