<?php

namespace App\Http\Controllers\Frontend\Product;

use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use App\Models\Product\ProductCategory;
use Inertia\Inertia;

class SaleController extends Controller
{
    public function index()
    {
        $items = ProductCategory::active()
            ->with('nodes.options', 'options')
            ->orderByRaw('-position DESC')
            ->where(['type' => ProductCategory::TYPE_SALE, 'parent_id' => 0])
            ->paginate(12)
            ->through(function ($item) {
                return $item->transformSale();
            });

        if (request()->wantsJson()) {
            return $items;
        }

        return Inertia::render(
            'Sale/Index',
            [
                'items' => $items
            ]
        );
    }

    public function show($slug, $id)
    {
        $item = ProductCategory::query()
            ->active()
            ->with([
                'options.children',
                'brands'
            ])
            ->findOrFail($id);

        $tags = $item->tags->map(fn ($item) => $item->transform());

        $query = Product::query()
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
            'categories' => [],
            'category' => $item->transformDetails(['is_show_options' => 1, 'is_show_brands' => 1, 'is_show_meta' => 1]),
            'tags' => $tags,
            'breadcrumb' => [
                [
                    "id" => 1,
                    "name" => "Sale sốc",
                    "slug" => "sale-soc"
                ],
                [
                    "id" => 2,
                    "name" => $item['name'],
                    "slug" => $item['slug'],
                ],
            ],
            'products' => $products
        ];

        if (request()->wantsJson()) {
            return response()->json($data);
        }

        return Inertia::render(
            'Categories/Show',
            $data
        );
    }
}
