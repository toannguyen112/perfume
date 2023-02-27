<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Blog\Slider;
use App\Models\Blog\Post;
use App\Models\Product\Product;
use App\Models\Product\ProductCategory;
use Inertia\Inertia;
use App\Http\Controllers\Frontend\BaseController;
use App\Models\Product\Brand;
use Jamstackvietnam\Support\Traits\ApiResponse;
use Jamstackvietnam\Support\Traits\HasApiCrudActions;

class HomeController extends BaseController
{
    use HasApiCrudActions, ApiResponse;

    public function index()
    {
        $productCategories = ProductCategory::active()
            ->where('is_home', 1)
            ->orderByRaw('-position DESC')
            ->take(10)
            ->get()
            ->map(fn ($item) => $item->transformHome());

        $brands = Brand::active()
            ->where('is_hot', 1)
            ->take(6)
            ->get()
            ->map(fn ($item) => $item->transform());

        $posts = Post::active()
            ->where('type', Post::TYPE_POST)
            ->where('is_home', 1)
            ->take(8)
            ->get()
            ->map(fn ($item) => $item->transform());

        $products = $this->getListProduct();

        $positions = [
            Slider::HOME_POSITION_TOP_LEFT,
            Slider::HOME_POSITION_TOP_RIGHT,
            Slider::HOME_POSITION_FOOTER_TOP,
            Slider::HOME_POSITION_FOOTER_BOTTOM_LEFT,
            Slider::HOME_POSITION_FOOTER_BOTTOM_CENTER,
            Slider::HOME_POSITION_FOOTER_BOTTOM_RIGHT,
        ];

        $sliders = Slider::active()
            ->whereIn('position', $positions)
            ->get()
            ->flatten()
            ->keyBy('position')
            ->map(fn ($item) => $item->transform());

        $data = [
            'sliders' => $this->getSlider(),
            'top_side_left' => $sliders[Slider::HOME_POSITION_TOP_LEFT] ?? null,
            'top_side_right' => $sliders[Slider::HOME_POSITION_TOP_RIGHT] ?? null,
            'footer_above' => $sliders[Slider::HOME_POSITION_FOOTER_TOP] ?? null,
            'footer_bottom_side_left' => $sliders[Slider::HOME_POSITION_FOOTER_BOTTOM_LEFT] ?? null,
            'footer_center' => $sliders[Slider::HOME_POSITION_FOOTER_BOTTOM_CENTER] ?? null,
            'footer_bottom_side_right' => $sliders[Slider::HOME_POSITION_FOOTER_BOTTOM_RIGHT] ?? null,

            'productCategories' => $productCategories,
            'brands' => $brands,
            'posts' => $posts,

            'saleProducts' => $products['sales'],
            'topViewProducts' => $products['topViews'],
            'newProducts' => $products['news'],
            'giftProducts' => $products['giftProducts'],
        ];

        if (request()->wantsJson()) {
            return response()->json($data);
        }

        return Inertia::render('Home', $data);
    }

    public function getListProduct()
    {
        $sales = Product::active()
            ->orderBy('id', 'DESC')
            ->where('is_sale', 1)
            ->take(5)
            ->get()
            ->map(fn ($item) => $item->transform());

        $topViews = Product::active()
            ->orderBy('is_hot', 'DESC')
            ->orderBy('views', 'DESC')
            ->orderBy('id', 'DESC')
            ->take(8)
            ->get()
            ->map(fn ($item) => $item->transform());

        $news = Product::query()
            ->active()
            ->orderBy('id', 'DESC')
            ->take(8)
            ->get()
            ->map(fn ($item) => $item->transform());

        $products = [
            'sales' => $sales,
            'topViews' => $topViews,
            'news' => $news,
            'giftProducts' => $this->giftProducts()->toArray(),
        ];

        return $products;
    }

    public function giftProducts()
    {
        $gifts = [
            'Quà tặng nữ',
            'Quà tặng nam',
            'Quà tặng doanh nghiệp'
        ];

        $category = ProductCategory::query()
            ->active()
            ->whereIn('name', $gifts)
            ->with('products', function ($query) {
                $query->active()->take(8);
            })
            ->get()
            ->map(fn ($item) => $item->transformHomeProduct());

        return $category;
    }

    public function getSlider()
    {
        return Slider::active()
            ->where('position', Slider::HOME_POSITION_SLIDER)
            ->orderByRaw('-position_sort DESC')
            ->take(5)
            ->get()
            ->map(fn ($item) => $item->transformDetails());
    }
}
