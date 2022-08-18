<?php

namespace Database\Seeders;

use App\Models\FavouriteProduct;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FavouriteProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FavouriteProduct::query()->truncate();

        $productIds = Product::query()->pluck('id');
        $userIds = User::query()->limit(10)->pluck('id');

        /** @var Generator $faker */
        $faker = app(Generator::class);

        $favouriteProductRows = [];
        $now = Carbon::now();
        foreach ($userIds as $userId) {
            foreach ($faker->randomElements($productIds, random_int(3, 12)) as $productId) {
                $favouriteProductRows[] = [
                    'created_at' => $now,
                    'updated_at' => $now,
                    'product_id' => $productId,
                    'user_id' => $userId,
                ];
            }
        }

        FavouriteProduct::query()->upsert($favouriteProductRows, ['user_id', 'product_id']);
    }
}
