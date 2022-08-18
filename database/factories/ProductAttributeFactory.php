<?php

namespace Database\Factories;

use App\Enums\ProductAttributeType;
use App\Models\ProductAttribute;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class ProductAttributeFactory extends Factory
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
            'type' => $this->faker->randomElement(array_values(ProductAttributeType::getConstants())),
            'sort_order' => self::$rowNumber,
            'external_id' => 'fake_external_id' . self::$rowNumber,
            'is_present_in_external_sources' => true,
        ];
    }
}
