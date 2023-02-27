<?php

namespace Database\Factories\Blog;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Blog\Post;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition()
    {
        $name = $this->faker->name;
        $metaTitle = $this->faker->word;
        return [
            'slug' => $name,
            'title' => Str::slug($name),
            'description' => $this->faker->sentence(rand(2, 5)),
            'content' => $this->faker->randomHtml(3, 10),
            'position' => rand(10, 1000),
            'is_home' => rand(0, 1),
            'is_featured' => rand(0, 1),

            'type' => 'POST',
            'status' => array_rand(Post::STATUS_LIST),

            'meta_title' => $metaTitle,
            'custom_slug' => Str::slug($metaTitle),
            'meta_description' => $this->faker->sentence(rand(2, 5))
        ];
    }
}
