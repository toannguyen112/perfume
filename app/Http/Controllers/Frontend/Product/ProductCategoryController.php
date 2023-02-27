<?php

namespace App\Http\Controllers\Frontend\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\Brand;
use App\Models\Product\Product;
use App\Models\Product\ProductCategory;
use Inertia\Inertia;

class ProductCategoryController extends Controller
{
    public function show($slug, $id)
    {
        $item = ProductCategory::query()
            ->active()
            ->with([
                'options.children',
                'brands',
                'parent',
                'parent.nodes'
            ])
            ->findOrFail($id);

        $categories = $item->nodes->count() != 0 ? $item->nodes : $item->parent->nodes;

        $tags = $item->tags->map(fn ($item) => $item->transform());

        $query = Product::query()
            ->active()
            ->filter(request()->all())
            ->whereHas('categories', function ($query) use ($id) {
                $query->whereId($id);
            });

        $products = $query->paginate(request()
            ->input('limit', 16))
            ->through(function (Product $item) {
                return $item->transform();
            })->withQueryString();

        $data = [
            'categories' => $categories->map(fn ($item) => $item->transform()),
            'category' => $item->transformDetails(['is_show_options' => 1, 'is_show_brands' => 1, 'is_show_meta' => 1]),
            'tags' => $tags,
            'breadcrumb' => ProductCategory::transformAsBreadcrumb($item),
            'products' => $products
        ];

        if (request()->wantsJson()) {
            return response()->json($data);
        }

        return Inertia::render('Categories/Show', $data);
    }
}
