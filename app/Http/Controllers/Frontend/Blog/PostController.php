<?php

namespace App\Http\Controllers\Frontend\Blog;

use App\Models\Blog\Post;
use App\Models\Blog\Slider;
use App\Models\Blog\PostCategory;
use Inertia\Inertia;
use App\Http\Controllers\Frontend\BaseController;
use Jamstackvietnam\Support\Traits\ApiResponse;
use Jamstackvietnam\Support\Traits\HasApiCrudActions;

class PostController extends BaseController
{

    use HasApiCrudActions, ApiResponse;

    public $model = Post::class;

    public function index()
    {
        return $this->renderDataPage(null, 'Blog/Post/Index');
    }

    public function categoryShow($categorySlug)
    {
        return $this->renderDataPage($categorySlug, 'Blog/Post/Index');
    }

    public function renderDataPage($slug, $path)
    {
        $categories = PostCategory::active()
            ->get()
            ->map(fn ($item) => $item->transform());

        $queryType = $this->model::query()
            ->where('type', Post::TYPE_POST)
            ->active();

        if (isset($slug)) {
            $queryType = $queryType
                ->with('categories')
                ->whereHas('categories', function ($query) use ($slug) {
                    $query->whereSlug($slug)->orWhere('custom_slug', $slug);
                });
        }

        $query = $queryType;

        $topPosts = $queryType
            ->orderBy('is_featured', 'DESC')
            ->orderBy('id', 'DESC')
            ->take(3)
            ->get()
            ->map(fn ($item) => $item->transform());

        $ids = collect($topPosts)->pluck('id')->toArray();

        $newPosts = $query
            ->whereNotIn('id', $ids)
            ->orderBy('created_at', 'DESC')
            ->paginate(12)
            ->through(function ($item) {
                return $item->transform();
            });

        $data = [
            'categories' => $categories,
            'bannerCenter' => $this->getSlider(Slider::POST_POSITION_CENTER),
            'bannerRight' => $this->getSlider(Slider::POST_POSITION_RIGHT),
            'topPosts' => $topPosts,
            'newPosts' => $newPosts
        ];

        if (request()->wantsJson()) {
            return response()->json($data);
        }

        return Inertia::render($path, $data);
    }

    public function getSlider($position)
    {
        return Slider::active()
            ->orderBy('position', 'DESC')
            ->where('position', $position)
            ->firstOrFail()
            ->transform();
    }

    public function show($categorySlug, $slug)
    {
        $item = Post::with(['relatedPosts' => fn ($query) => $query->take(8)->active()])
            ->where('type', Post::TYPE_POST)
            ->whereSlug($slug)
            ->orWhere('custom_slug', $slug)
            ->firstOrFail()
            ->transformDetails();

        $postNews = Post::active()
            ->where('type', Post::TYPE_POST)
            ->orderBy('id', 'DESC')
            ->take(5)
            ->get()
            ->map(fn ($item) => $item->transformDetails());

        $relatedPosts = $this->relatedPosts($slug, $item);

        return Inertia::render('Blog/Post/Show', [
            'item' => $item,
            'relatedPosts' => $relatedPosts,
            'postNews' => $postNews
        ]);
    }

    public function relatedPosts($slug, $item)
    {
        $postRelated = Post::with(['relatedPosts' => fn ($query) => $query->take(8)->active()])
            ->where('type', Post::TYPE_POST)
            ->whereSlug($slug)
            ->orWhere('custom_slug', $slug)
            ->firstOrFail()
            ->getRelated();

        $additionItems = [];
        $additionItemsCount = 8 - count($postRelated);
        if ($additionItemsCount > 0) {
            $additionItems = Post::query()
                ->active()
                ->where('title', 'LIKE', '%' . $item['title'] . '%')
                ->where('type', Post::TYPE_POST)
                ->take($additionItemsCount)
                ->get();
        }

        $mapRelated = $postRelated['relatedPosts'];

        return $mapRelated->concat($additionItems)->map(fn ($mapRelated) => $mapRelated->transformDetails());
    }
}
