<?php

namespace App\Http\Controllers\Frontend\Blog;

use App\Models\Blog\Post;
use Inertia\Inertia;
use App\Http\Controllers\Frontend\BaseController;

class PolicyController extends BaseController
{

    public function index()
    {
        $items = $this->getList();
        return Inertia::render('Blog/Policy/Index', [
            'items' => $items
        ]);
    }

    public function show($slug)
    {
        $items = $this->getList();

        $content = Post::whereSlug($slug)
            ->orWhere('custom_slug', $slug)
            ->firstOrFail()
            ->transform();

        return Inertia::render('Blog/Policy/Show', [
            'list_sidebar' => $items,
            'content' => $content
        ]);
    }

    public function getList()
    {
        return Post::active()
            ->where('type', Post::TYPE_POLICY)
            ->get()
            ->map(fn ($item) => $item->transform());
    }
}
