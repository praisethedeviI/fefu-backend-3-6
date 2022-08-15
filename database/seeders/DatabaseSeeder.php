<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            SettingsSeeder::class,
            UserSeeder::class,
            PageSeeder::class,
            NewsSeeder::class,
            ProductCategorySeeder::class,
            ProductAttributeSeeder::class,
            ProductSeeder::class,
            ProductAttributeValueSeeder::class,
            FavouriteProductSeeder::class,
        ]);
    }
}
