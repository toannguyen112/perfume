<?php

namespace Database\Seeders;

use App\Models\Product\Product;
use App\Models\Product\ProductCategory;
use App\Models\Product\Option;
use App\Models\Tag;
use App\Models\Blog\Post;
use App\Models\Blog\PostCategory;
use App\Models\Product\Brand;
use App\Models\Blog\Job;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // RoleSeeder::class,
            // Option::class,
            // Tag::class,
            // Post::class,
            // Brand::class,
            // Job::class,
        ]);
    }
}
