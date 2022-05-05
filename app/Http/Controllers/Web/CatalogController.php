<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class CatalogController extends Controller
{
    /**
     * Display catalog of product categories.
     *
     * @param string|null $slug
     * @return Application|Factory|View
     */
    public function index(string $slug = null): View|Factory|Application
    {
        $query = ProductCategory::query()->with('children', 'products');

        if ($slug === null) {
            $query->where('parent_id');
        } else {
            $query->where('slug', $slug)->firstOrFail();
        }

        $categories = $query->get();
        $products = ProductCategory::getTreeProductBuilder($categories)
            ->orderBy('id')
            ->paginate();

        return view('catalog.catalog', ['categories' => $categories, 'products' => $products]);
    }
}
