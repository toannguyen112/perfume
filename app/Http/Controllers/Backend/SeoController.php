<?php

namespace App\Http\Controllers\Backend;

use App\Models\Seo;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\Product\ProductController;
use App\Models\Product\Brand;
use App\Models\Product\ProductCategory;
use Inertia\Inertia;

class SeoController extends Controller
{
    public $model = Seo::class;

    public function index()
    {
        $categories = ProductCategory::where('type', ProductCategory::TYPE_PRODUCT)->get();
        $brands = Brand::get();
        return Inertia::render('Seo/Index', [
            'categories' => $categories,
            'brands' => $brands,
        ]);
    }

    public function form()
    {
        $slug = request()->input('slug');

        $page = (new ProductController)->index($slug);

        $page['brands'] = collect($page['brands'])
            ->reject(fn ($item) => isset($item['active']) && $item['active'] === true)
            ->map(function ($item) use ($page) {
                $query = Seo::query()->where('brand_id', $item['id']);

                if ($page['product_category']) {
                    $query = $query->where('product_category_id', $page['product_category']['id']);
                }

                $item['has_seo'] = $query->exists();
                return $item;
            });

        $page['options'] = collect($page['options'])
            ->map(function ($item) use ($page) {
                $item['children'] = collect($item['children'])->map(function ($item) use ($page) {
                    $query = Seo::query()->whereJsonContains('option_ids', [$item['id']]);

                    if ($page['product_category']) {
                        $query = $query->where('product_category_id', $page['product_category']['id']);
                    }

                    if ($page['brand']) {
                        $query = $query->where('brand_id', $page['brand']['id']);
                    }

                    $item['has_seo'] = $query->exists();
                    return $item;
                })->toArray();
                return $item;
            })->values();

        return Inertia::render('Seo/Form', [
            'page' => $page,
            'rules' => (new $this->model)->modelRules()['all'],
        ]);
    }

    public function store()
    {
        $data = request()->input();
        $meta = $data['meta'];
        $meta['option_ids'] = collect($data['options'])
            ->where('active', true)
            ->pluck('children')
            ->flatten(1)
            ->where('active', true)
            ->pluck('id')
            ->sort()
            ->toArray();

        if (
            (!empty($data['product_category'] && !empty($data['brand']))) ||
            (!empty($meta['option_ids']) && (empty($data['product_category'] || empty($data['brand']))))
        ) {
            $productCategoryId = $data['product_category']['id'] ?? null;
            $brandId = $data['brand']['id'] ?? null;

            $seo = $this->model::query()
                ->where('brand_id', $brandId)
                ->where('product_category_id', $productCategoryId)
                ->whereJsonContains('option_ids', $meta['option_ids'])
                ->first();
        } else if (empty($meta['option_ids'])) {
            if ($meta['page_type'] === 'PRODUCT_CATEGORY') {
                $seo = ProductCategory::find($meta['id']);
            } else if ($meta['page_type'] === 'BRAND') {
                $seo = Brand::find($meta['id']);
            }
        }

        if (isset($seo)) {
            $seo->update($meta);
        } else {
            if (!isset($meta['page_type'])) {
                $meta['product_category_id'] = $meta['product_category_id'];
                $meta['brand_id'] = $meta['brand_id'];
            } else if ($meta['page_type'] === 'PRODUCT_CATEGORY') {
                $meta['product_category_id'] = $meta['id'];
            } else if ($meta['page_type'] === 'BRAND') {
                $meta['brand_id'] = $meta['id'];
            }
            $this->model::create($meta);
        }

        return back()->with('success', 'Lưu đối tượng thành công.');
    }
}
