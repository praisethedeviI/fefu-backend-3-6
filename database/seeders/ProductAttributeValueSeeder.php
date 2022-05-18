<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use App\Models\ProductCategory;
use Exception;
use Faker\Generator;
use Illuminate\Database\Seeder;
use function random_int;

class ProductAttributeValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run()
    {
        ProductAttributeValue::query()->delete();

        $products = Product::get();
        $attributes = ProductAttribute::get();
        $categoryIds = ProductCategory::pluck('id')->all();

        /** @var Generator $faker */
        $faker = app(Generator::class);
        $attributeIdsByCategoryId = [];
        foreach ($categoryIds as $categoryId) {
            $attributeIdsByCategoryId[$categoryId] = $faker->randomElements($attributes, random_int(3, 5));
        }


        foreach ($products as $product) {
            foreach ($attributeIdsByCategoryId[$product->product_category_id] as $attribute) {
                if ($faker->boolean(90)) {
                    ProductAttributeValue::factory()->product($product->id)->attribute($attribute)->create();
                }
            }
        }
    }
}
