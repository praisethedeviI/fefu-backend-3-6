<?php

namespace Database\Factories;

use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class ProductCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word,

        ];
    }

    public function child(int $parentId): self
    {
        return $this->state(function () use ($parentId) {
            return [
                'parent_id' => $parentId,
            ];
        });
    }
}
