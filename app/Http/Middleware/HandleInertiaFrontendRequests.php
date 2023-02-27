<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Middleware;
use App\Models\Product\ProductCategory;
use App\Models\Blog\Post;
use App\Models\Product\Brand;
use App\Models\Blog\Slider;
use App\Models\Blog\PostCategory;
use App\Models\Nav;
use App\Models\Product\ShoppingCart;
use App\Models\Product\Product;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaFrontendRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'frontend';

    /**
     * Determine the current asset version.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request)
    {
        $data = array_merge(parent::share($request), [
            'auth' => [
                'is_admin' => auth()->guard('admin')->check(),
                'user' => auth()->guard('user')->user(),
            ],
            'config' => [
                'locales' => config('translatable.locales'),
            ],
            'flash' => function () use ($request) {
                return [
                    'success' => $request->session()->get('success'),
                    'error' => $request->session()->get('error'),
                ];
            },
            'navs' => $this->navs(),
            'menu' => [
                'brands' => $this->brands(),
                'categories' => $this->categories(),
            ],
            'blog' => $this->blog(),
            'suggest_products' => $this->suggestProducts(),
            'footer' => $this->footer(),
            'cart' => $this->cart(),
            'ziggy' => (new Ziggy(null, $request->url()))->toArray(),
        ]);

        return $data;
    }

    public function cart()
    {
        $item = new ShoppingCart();
        return  $item->getContent();
    }

    private function blog()
    {
        $blog = PostCategory::active()
            ->orderBy('id', 'DESC')
            ->get()
            ->map(fn ($item) => $item->transform());

        return $blog;
    }

    private function footer()
    {
        $policies = Post::query()
            ->active()
            ->where('type', Post::TYPE_POLICY)
            ->orderBy('id', 'DESC')
            ->take(4)
            ->get()
            ->map(fn ($item) => $item->transformTypePolicy());

        return $policies;
    }

    public function suggestProducts()
    {
        return Product::query()
            ->active()
            ->orderBy('views', 'desc')
            ->take(10)
            ->get()
            ->map(fn ($item) => $item->transform());
    }

    private function navs()
    {
        return Nav::getRoot()
            ->map(fn ($item) => $item->transform());
    }

    private function brands()
    {
        $brand = [];

        $positions = [
            Slider::HEADER_BRAND_POSITION_LEFT,
            Slider::HEADER_BRAND_POSITION_CENTER,
            Slider::HEADER_BRAND_POSITION_RIGHT,
        ];

        $banner = Slider::active()
            ->whereIn('position', $positions)
            ->get()
            ->flatten()
            ->keyBy('position')
            ->map(fn ($item) => $item->transform());

        $brand = [
            'id' => 1,
            'name' => 'Thương hiệu',
            'slug' => 'thuong-hieu',
            'icon' => '<svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M22.943 10.4406C22.557 10.0445 22.1609 9.62813 22.0086 9.27266C21.8562 8.91719 21.8664 8.38906 21.8562 7.85078C21.8461 6.86563 21.8258 5.73828 21.0437 4.95625C20.2617 4.17422 19.1344 4.15391 18.1492 4.14375C17.6109 4.13359 17.0625 4.12344 16.7273 3.99141C16.3922 3.85938 15.9555 3.44297 15.5594 3.05703C14.8586 2.38672 14.0562 1.625 13 1.625C11.9438 1.625 11.1414 2.38672 10.4406 3.05703C10.0445 3.44297 9.62813 3.83906 9.27266 3.99141C8.91719 4.14375 8.38906 4.13359 7.85078 4.14375C6.86563 4.15391 5.73828 4.17422 4.95625 4.95625C4.17422 5.73828 4.15391 6.86563 4.14375 7.85078C4.13359 8.38906 4.12344 8.9375 3.99141 9.27266C3.85938 9.60781 3.44297 10.0445 3.05703 10.4406C2.38672 11.1414 1.625 11.9438 1.625 13C1.625 14.0562 2.38672 14.8586 3.05703 15.5594C3.44297 15.9555 3.83906 16.3719 3.99141 16.7273C4.14375 17.0828 4.13359 17.6109 4.14375 18.1492C4.15391 19.1344 4.17422 20.2617 4.95625 21.0437C5.73828 21.8258 6.86563 21.8461 7.85078 21.8562C8.38906 21.8664 8.9375 21.8766 9.27266 22.0086C9.60781 22.1406 10.0445 22.557 10.4406 22.943C11.1414 23.6133 11.9438 24.375 13 24.375C14.0562 24.375 14.8586 23.6133 15.5594 22.943C15.9555 22.557 16.3719 22.1609 16.7273 22.0086C17.0828 21.8562 17.6109 21.8664 18.1492 21.8562C19.1344 21.8461 20.2617 21.8258 21.0437 21.0437C21.8258 20.2617 21.8461 19.1344 21.8562 18.1492C21.8664 17.6109 21.8766 17.0625 22.0086 16.7273C22.1406 16.3922 22.557 15.9555 22.943 15.5594C23.6133 14.8586 24.375 14.0562 24.375 13C24.375 11.9438 23.6133 11.1414 22.943 10.4406ZM18.0273 11.1516L12.0758 16.8391C11.9219 16.9838 11.7183 17.0638 11.507 17.0625C11.2989 17.0633 11.0986 16.9832 10.9484 16.8391L7.97266 13.9953C7.89012 13.9233 7.82299 13.8353 7.77532 13.7367C7.72764 13.6381 7.70039 13.5309 7.69522 13.4215C7.69004 13.3121 7.70704 13.2027 7.74519 13.1001C7.78335 12.9974 7.84187 12.9035 7.91724 12.824C7.99261 12.7445 8.08327 12.6811 8.18378 12.6375C8.28428 12.594 8.39256 12.5712 8.50209 12.5706C8.61162 12.5699 8.72015 12.5915 8.82116 12.6338C8.92216 12.6762 9.01356 12.7386 9.08984 12.8172L11.507 15.1227L16.9102 9.97344C17.0681 9.83561 17.2733 9.76418 17.4827 9.77409C17.6921 9.78399 17.8896 9.87447 18.0339 10.0266C18.1781 10.1787 18.258 10.3807 18.2568 10.5904C18.2556 10.8 18.1734 11.0011 18.0273 11.1516Z" fill="black"/>
</svg>
',
            'links' => [
                [
                    'image_url' => $banner[Slider::HEADER_BRAND_POSITION_LEFT]['thumbnail'] ?? null,
                    'url' => $banner[Slider::HEADER_BRAND_POSITION_LEFT]['link'] ?? null
                ],
                [
                    'image_url' => $banner[Slider::HEADER_BRAND_POSITION_CENTER]['thumbnail'] ?? null,
                    'url' => $banner[Slider::HEADER_BRAND_POSITION_CENTER]['link'] ?? null
                ],
                [
                    'image_url' => $banner[Slider::HEADER_BRAND_POSITION_RIGHT]['thumbnail'] ?? null,
                    'url' => $banner[Slider::HEADER_BRAND_POSITION_RIGHT]['link'] ?? null
                ],
            ],
            'items' => Brand::active()->get()->map(fn ($item) => $item->transform())
        ];

        return $brand;
    }

    private function categories()
    {
        return ProductCategory::getNav()->map(fn ($item) => $item->transformNav());
    }
}
