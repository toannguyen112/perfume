<?php

namespace App\Http\Controllers\Frontend\Product;

use App\Models\Seo;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Product\Brand;
use App\Models\Product\Option;
use App\Models\Product\Comment;
use App\Models\Product\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Container\Container;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use App\Models\Product\ProductCategory;
use Jamstackvietnam\Support\Models\File;
use Illuminate\Pagination\LengthAwarePaginator;
use Jamstackvietnam\Support\Traits\ApiResponse;
use Jamstackvietnam\Support\Traits\HasApiCrudActions;

class ProductController extends Controller
{
    use ApiResponse, HasApiCrudActions;

    public $model = Product::class;
    public function index($segments)
    {
        $segments = explode('/', $segments);

        $count = count($segments);

        $data = [];

        $category = null;
        $optionSlugs = [];

        $brand = Brand::query()
            ->whereSlug($segments[0])
            ->active()
            ->first();

        if ($count === 1 && empty($brand)) {
            $category = ProductCategory::query()
                ->typeProductActive()
                ->withoutEagerLoads()
                ->with('children', function($query) {
                    $query->typeProductActive();
                })
                ->whereSlug($segments[0])
                ->firstOrFail();

            $optionSlugs = array_slice($segments, 1, $count);
        } else if ($count > 1) {
            if (!empty($brand)) {
                $category = ProductCategory::query()
                    ->typeProductActive()
                    ->withoutEagerLoads()
                    ->with('children', function($query) {
                        $query->typeProductActive();
                    })
                    ->whereSlug($segments[1])
                    ->first();

                $optionIndex = empty($category) ? 1 : 2;

                $optionSlugs = array_slice($segments, $optionIndex, $count);
            } else {
                $category = ProductCategory::query()
                    ->typeProductActive()
                    ->withoutEagerLoads()
                    ->with('children', function($query) {
                        $query->typeProductActive();
                    })
                    ->whereSlug($segments[0])
                    ->first();

                $optionIndex = empty($category) ? 0 : 1;

                $optionSlugs = array_slice($segments, $optionIndex, $count);
            }
        }

        $categoryIds = [];

        if ($category) {
            $data['meta'] = $category->transformDetails();
            $data['meta']['page_type'] = 'PRODUCT_CATEGORY';
        } else if ($brand) {
            $data['meta'] = $brand->transformDetails();
            $data['meta']['page_type'] = 'BRAND';
        }

        if ($category) {
            $categoryIds = $category->transformChildrenIds();
            $data['breadcrumb'] = $category->transformToBreadcrumbItems();
        }

        if (empty($brand)) {
            $data['categories'] = $category?->transformChildren();
        }

        $data['product_category'] = $category?->transform();
        $data['brand'] = $brand?->transform();

        $data = array_merge(
            $data,
            $this->getProducts($categoryIds, $optionSlugs, $brand)
        );

        $optionIds = collect($data['options'])
            ->where('active', true)
            ->pluck('children')
            ->flatten(1)
            ->where('active', true)
            ->pluck('id')
            ->sort()
            ->toArray();

        if (
            (!empty($data['product_category'] && !empty($data['brand']))) ||
            (!empty($optionIds) && (empty($data['product_category'] || empty($data['brand']))))
        ) {
            $productCategoryId = $data['product_category']['id'] ?? null;
            $brandId = $data['brand']['id'] ?? null;

            $data['meta'] = Seo::findSeoData($productCategoryId, $brandId, $optionIds);
        }

        if (request()->route()->getPrefix() === 'admin') {
            return $data;
        }

        if (request()->wantsJson()) {
            return response()->json($data);
        }

        return Inertia::render('Categories/Show', $data);
    }

    public function show($slug, $id)
    {
        $variantId = request('id', null);

        $product = Product::query()
            ->active()
            ->with([
                'brand',
                'brand.files',
                'categories.parent.parent.parent',
            ])
            ->with('comments', function ($query) {
                $query->active()->whereJsonLength('images', '>', 0);
            })
            ->findOrFail($id);


        $productSlug = $product->custom_slug ?? $product->slug;

        if ($slug != $productSlug) {
            $query = request()->query();
            $route = route('product.show', [
                'productSlug' => $productSlug,
                'productId' => $product->id,
            ]);

            $url = empty($query) ? $route : $route . '?' . http_build_query($query);

            return redirect($url, 301);
        }

        $product->increment('views');

        $commentImages = $product->comments
            ->map(fn ($item) => collect($item->images)->map(fn ($item) => static_url($item['image_url'])))
            ->flatten(1);

        $comments = Comment::active()->select('rate_score', DB::raw('count(id) as count'))
            ->groupBy('rate_score')
            ->where('product_id', $product->id)
            ->get();

        $total = $comments->sum('count');

        $rateScore = $comments->avg('rate_score') ?? 0;

        $ratings = [];
        for ($i = 0; $i < 5; $i++) {
            $comment = $comments->firstWhere('rate_score', $i + 1);
            $ratings[$i] = $comment?->count == 0  ? 0 : round($comment?->count / $total * 100);
        }

        $categoryIds = $product->categories->pluck('id');

        $relatedProducts = Product::query()
            ->active()
            ->whereHas('categories', function($query) use ($categoryIds){
                $query->whereIn('id', $categoryIds);
            })
            ->where('id', '<>', $product->id)
            ->take(10)
            ->get()
            ->map(fn ($item) => $item->transform());

        $data = [
            'item' => $product->transformDetails($variantId),
            'related_products' => $relatedProducts,
            'comment' => [
                'rateScore' => $rateScore,
                'total' => $total,
                'ratings' => $ratings,
                'images' => $commentImages
            ],
        ];

        if (request()->wantsJson()) {
            return $data;
        }

        return inertia('Products/Show', $data);
    }

    public function comment(Request $request, $slug, $id)
    {
        $errors = $this->validateRequest(__FUNCTION__, Comment::class);

        if (count($errors) > 0) {
            if (request()->wantsJson()) {
                return $this->failure($errors);
            }
            return back()->with('error', 'Bình luận thất bại');
        }

        $data = $request->all();
        $item = $this->model::query()
            ->active()
            ->findOrFail($id);

        if (empty($item->id)) {
            return back()->with('error', 'Không tìm thấy sản phẩm');
        }

        $files = $request->file('images');
        $fileUploaded = [];
        if ($files && count($files) > 0) {

            $uploader = auth()->guard('admin')->user();

            foreach ($files as $file) {
                $fileUploaded[] = File::storeFile($file, $uploader, 'guest');
            }

            $fileUploaded = collect($fileUploaded)->values()->map(fn ($item) => [
                'id' => $item->id,
                'path' => $item->path,
                'extension' => $item->extension,
                'image_url' => $item->image_url,
                'size' => $item->size,
            ]);
        }

        $data['images'] = $fileUploaded;
        $data['product_id'] = $item->id;

        $resource = $item->comments()->create($data);
        if (request()->wantsJson()) {
            return $resource->transform();
        }

        return back()->withSuccess('Message', 'Bình luận sản phẩm thành công');
    }

    public function getCommentsProduct($slug, $id)
    {
        $query = Comment::active()
            ->whereHas('product', function ($query) use ($id) {
                $query->where('id', $id);
            })
            ->filter(request()->all());

        $items =
            $query->paginate(6)
            ->through(function ($item) {
                return $item->transform();
            });

        return $this->success($items);
    }

    public function instantSearch()
    {
        $data = [
            'suggestions' => [],
            'products' => [],
        ];

        if ($keyword = request()->input('keyword')) {
            $data['products'] = Product::query()
                ->active()
                ->filter(request()->all())
                ->take(10)
                ->get()->map(fn ($item) => $item->transform());

            $data['suggestions'] = [$keyword];
        }

        return response()->json($data);
    }

    public function search()
    {
        $breadcrumb =  [[
            "id" => 1,
            "name" => "Tìm kiếm",
            "slug" => "tim-kiem"
        ]];

        return $this->handleProducts($breadcrumb, 'search');
    }

    public function new()
    {
        $breadcrumb =  [[
            "id" => 1,
            "name" => "New",
            "slug" => "new"
        ]];

        return $this->handleProducts($breadcrumb, 'new');
    }

    public function handleProducts($breadcrumb = [], $type = 'search')
    {
        $condition = request()->all();

        $products = [];
        if ($type === 'search' && !request()->has('keyword')) {
            $products = [];
        } else {
            $products = Product::query()
                ->active()
                ->filter($condition)
                ->paginate(12)
                ->through(fn ($item) => $item->transform())
                ->withQueryString();
        }

        $categories = ProductCategory::treeList([ProductCategory::TYPE_PRODUCT]);

        $options = Option::query()
            ->with('children')
            ->active()
            ->orderBy('position')
            ->orderBy('parent_id')
            ->orWhere('parent_id', 0)
            ->take(10)
            ->get()->map(fn ($item) => $item->transform());

        $brands = Brand::list();

        $data =  [
            'categories' => $categories,
            'breadcrumb' => $breadcrumb,
            'tags' => [],
            'category' => [
                'options' => $options,
                'brands' => $brands,
            ],
            'products' => $products,
        ];

        if (empty($products)) {
            $data['otherProducts'] = Product::query()
                ->active()
                ->orderBy('views', 'DESC')
                ->orderBy('id', 'DESC')
                ->paginate(20)
                ->through(fn ($item) => $item->transform())
                ->withQueryString();
        }

        if (request()->wantsJson()) {
            return $data;
        }

        return inertia('Categories/Show', $data);
    }

    public function routeFilters()
    {
        $options = array_filter(request()->input(), function ($key) {
            return strpos($key, 'opt-') === 0;
        }, ARRAY_FILTER_USE_KEY);

        $optionIds = collect($options)->map(function ($values) {
            return explode(',', $values);
        })->flatten(1);

        $options = Option::query()
            ->with('parent')
            ->active()
            ->whereChildren()
            ->whereIn('id', $optionIds)
            ->get()
            ->sortByDesc('parent.position')
            ->sortBy('parent.name')
            ->groupBy('parent_id')
            ->values()
            ->flatten(1);

        $categorySlug = request()->query('category');
        $brandSlug = request()->query('brand');
        $slugs = $options->pluck('slug')->toArray();
        array_unshift($slugs, $categorySlug);

        if ($categorySlug !== $brandSlug) {
            array_unshift($slugs, $brandSlug);
        }

        $slug = join('/', array_filter($slugs));

        $queries = [
            'rate_score' => request()->query('rate_score'),
            'price' => request()->query('price'),
        ];
        $queries = http_build_query(collect($queries)->filter()->toArray());

        if (request()->query('is_admin')) {
            $url = route('sidebar.seo.form', ['slug' => $slug]);
        } else {
            $url = route('products.index', ['slug' => $slug]);
        }

        if ($queries) {
            $url .= "?$queries";
        }

        if (request()->query('is_admin')) {
            return response()->json($url);
        }

        return redirect($url);
    }

    public function getProducts($categoryIds, $optionSlugs = [], $brand = null)
    {
        $condition = request()->all();
        $condition['category_ids'] = $categoryIds;
        $condition['option_slugs'] = $optionSlugs;

        if (!empty($brand)) {
            $condition['brand_id'] = $brand->id;
        }

        $products = Product::query()
            ->with('parentOptions', function ($query) {
                $query->active()->whereRoot();
            })
            ->with('childrenOptions', function ($query) {
                $query->active();
            })
            ->with('brand', function ($query) {
                $query->active();
            })
            ->active()
            ->filter($condition)
            ->distinct()
            ->get();

        if (!empty($brand)) {
            $brand = $brand->transform();
            $brand['active'] = true;
            $brands[] = $brand;
        } else {
            $brands = $products->pluck('brand')->filter()
                ->unique()->values()
                ->map(fn ($item) => $item->transform());
        }

        $parentOptions = $products->pluck('parentOptions')->flatten(1)->unique('id');
        $childrenOptions = $products->pluck('childrenOptions')->flatten(1)->unique('id');

        $options = Option::transformNestedFrontend(
            $parentOptions,
            $childrenOptions,
            ['active_slugs' => $optionSlugs]
        );

        if (!empty($brand)) {
            $productIds = $products->pluck('id');
            $brandCategories = ProductCategory::query()
                ->active()
                ->whereHas('products', function ($query) use ($productIds) {
                    $query->active()->whereIn('id', $productIds);
                })
                ->orderByRaw('-position DESC')
                ->get()->each
                ->transform();
        }

        $products = $this->paginate($products, 20)->through(function ($item) {
            return $item->transform();
        });;

        return [
            'products' => $products,
            'options' => $options,
            'brands' => $brands,
            'brand_categories' => $brandCategories ?? [],
            'tags' => [],
        ];
    }

    public function paginate(Collection $results, $showPerPage)
    {
        $pageNumber = Paginator::resolveCurrentPage('page');

        $totalPageNumber = $results->count();

        return $this->paginator($results->forPage($pageNumber, $showPerPage), $totalPageNumber, $showPerPage, $pageNumber, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page',
        ]);
    }

    /**
     * Create a new length-aware paginator instance.
     *
     * @param  \Illuminate\Support\Collection  $items
     * @param  int  $total
     * @param  int  $perPage
     * @param  int  $currentPage
     * @param  array  $options
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    protected function paginator($items, $total, $perPage, $currentPage, $options)
    {
        return Container::getInstance()->makeWith(LengthAwarePaginator::class, compact(
            'items',
            'total',
            'perPage',
            'currentPage',
            'options'
        ));
    }
}
