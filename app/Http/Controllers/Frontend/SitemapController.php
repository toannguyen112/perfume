<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Jamstackvietnam\Sitemap\Sitemap;
use App\Models\Product\ProductCategory;
use App\Models\Product\Product;
use App\Models\Product\Brand;
use App\Models\Blog\PostCategory;
use App\Models\Blog\Post;
use App\Models\Blog\Job;

class SitemapController extends Controller
{
    public function index()
    {
        return Sitemap::create()
            ->addStaticRoutes()
            ->add(ProductCategory::active()->get())
            ->add(Product::active()->get())
            ->add(Brand::active()->get())
            ->add(Post::active()->get())
            ->add(PostCategory::active()->get())
            ->add(Job::active()->get())
            ->render();
    }
}
