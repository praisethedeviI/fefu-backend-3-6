<?php

namespace Database\Seeders;

use App\Models\ProductAttribute;
use Database\Factories\ProductAttributeFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductAttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductAttribute::query()->truncate();
        ProductAttribute::factory(random_int(20, 30))->create();
    }
}
