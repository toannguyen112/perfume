<?php

namespace App\Http\Controllers\Frontend\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\Brand;
use App\Models\Product\Product;
use App\Models\Product\ProductCategory;
use Inertia\Inertia;

class BrandController extends Controller
{
    public function Index()
    {
        $items = Brand::query()
            ->active()
            ->orderBy('name', 'DESC')
            ->get()
            ->map(fn ($item) => $item->transform());

        if (request()->wantsJson()) {
            return response()->json($items);
        }

        return inertia('Brands/Index', [
            'items' => $items,
        ]);
    }

    public function show($slug)
    {
        $brand = Brand::whereSlug($slug)
            ->first()
            ->transformDetails();

        $categories = ProductCategory::query()
            ->active()
            ->with([
                'options.children',
                'brands',
                'parent.nodes',
                'parent',
                'tags'
            ])
            ->whereHas('brands', function ($query) use ($slug) {
                $query->where('slug', $slug)
                    ->orWhere('custom_slug', $slug);
            })->get();

        $query = Product::query()
            ->whereHas('brand', function ($query) use ($slug) {
                $query->whereSlug($slug);
            })
            ->filter(request()->all());

        $products = $query->paginate(request()->input('limit', 20))->through(function (Product $item) {
            return $item->transform();
        })->withQueryString();

        $brand['options'] = [];
        $brand['brands'] = [];

        $data = [
            'category' => $brand,
            'tags' => [],
            'breadcrumb' => [
                [
                    "id" => 1,
                    "name" => "Thương hiệu",
                    "slug" => "thuong-hieu"
                ],
                [
                    "id" => 2,
                    "name" => $brand['name'],
                    "slug" => $brand['slug'],
                ],
            ],
            'categories' => $categories->map(fn ($item) => $item->transformDetails(['is_show_nodes' => 1])),
            'products' => $products,
        ];

        if (request()->wantsJson()) {
            return $data;
        }

        return Inertia::render('Categories/Show', $data);
    }
}
