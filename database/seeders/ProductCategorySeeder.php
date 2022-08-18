<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    private const DEPTH = 4;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductCategory::query()->truncate();
        $this->seedChildren(ProductCategory::factory(random_int(1, 3))->create()->pluck('id'));
    }

    private function seedChildren(iterable $parentIds, int $depth = 1)
    {
        if ($depth > self::DEPTH) {
            return;
        }
        foreach ($parentIds as $parentId) {
            $this->seedChildren(ProductCategory::factory(random_int(1, 3))->child($parentId)->create()->pluck('id'), ++$depth);
        }
    }
}
