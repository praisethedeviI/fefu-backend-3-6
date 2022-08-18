<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

/**
 * @extends Factory
 */
class ProductFactory extends Factory
{
    private $categoryIds;
    public static $rowNumber = 0;

    public function __construct($count = null, ?Collection $states = null, ?Collection $has = null, ?Collection $for = null, ?Collection $afterMaking = null, ?Collection $afterCreating = null, $connection = null)
    {
        parent::__construct($count, $states, $has, $for, $afterMaking, $afterCreating, $connection);
        $this->categoryIds = ProductCategory::pluck('id')->all();
    }

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
            'description' => $this->faker->realTextBetween(50, 150),
            'price' => $this->faker->randomFloat(8, 100, 10000),
            'product_category_id' => $this->faker->randomElement($this->categoryIds),
            'external_id' => 'fake_external_id' . self::$rowNumber,
            'is_present_in_external_sources' => true,
        ];
    }
}
