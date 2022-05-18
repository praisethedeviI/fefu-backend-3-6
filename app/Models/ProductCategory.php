<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;


/**
 * @mixin IdeHelperProductCategory
 */
class ProductCategory extends Model
{
    use HasFactory, Sluggable;

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * @throws Exception
     */
    public static function getTreeProductBuilder(Collection $categories): Builder
    {
        if ($categories->isEmpty()) {
            throw new Exception('Empty categories collection');
        }
        $categoryIds = [];

        $collectCategoryIds = function (ProductCategory $category) use (&$categoryIds, &$collectCategoryIds) {
            $categoryIds[] = $category->id;
            foreach ($category->children as $childCategory) {
                $collectCategoryIds($childCategory);
            }
        };

        foreach ($categories as $category) {
            $collectCategoryIds($category);
        }

        return Product::query()->whereIn('product_category_id', $categoryIds);
    }
}
