<?php

namespace Database\Factories;

use App\Models\News;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory
 */
class NewsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'published_at' => $this->faker->boolean ?
                $this->faker->dateTimeBetween('-2 weeks', '+2 weeks') :
                null,
            'title' => $this->faker->realTextBetween(10, 25),
            'text' => $this->faker->realTextBetween(100, 1000),
        ];
    }
}
