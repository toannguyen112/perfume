<?php

namespace Database\Factories\Blog;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Blog\PostCategory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PostCategoryFactory extends Factory
{
    protected $model = PostCategory::class;

    public function definition()
    {
        $name = $this->faker->name;
        $metaTitle = $this->faker->word;
        return [
            'title' => $name,
            'slug' => Str::slug($name),

            'status' => array_rand(PostCategory::STATUS_LIST),

            'meta_title' => $metaTitle,
            'custom_slug' => Str::slug($metaTitle),
            'meta_description' => $this->faker->sentence(rand(2, 5)),
        ];
    }
}
