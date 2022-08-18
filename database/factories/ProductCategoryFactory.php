<?php

namespace Database\Factories;

use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class ProductCategoryFactory extends Factory
{
    public static $rowNumber = 0;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        self::$rowNumber++;
        return [
            'name' => $this->faker->word,
            'external_id' => 'fake_external_id' . self::$rowNumber,
            'is_present_in_external_sources' => true,
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
