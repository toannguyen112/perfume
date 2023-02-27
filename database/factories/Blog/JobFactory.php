<?php

namespace Database\Factories\Blog;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Blog\Job;
use Illuminate\Support\Str;

class JobFactory extends Factory
{
    protected $model = Job::class;

    public function definition()
    {
        $name = $this->faker->name;
        $metaTitle = $this->faker->word;
        return [
            'slug' => $name,
            'title' => Str::slug($name),
            'description' => $this->faker->text,
            'position' => $this->faker->text,
            'salary' => $this->faker->randomFloat(),
            'address' => $this->faker->address,
            'work_form' => $this->faker->text,
            'work_time' => $this->faker->date(),
            'count' => $this->faker->randomNumber(1,20),

            'status' => array_rand(Job::STATUS_LIST),
            'expected_time' => $this->faker->date(),
            'is_featured' => $this->faker->boolean,
            'posted_at' => $this->faker->date,

            'meta_title' => $metaTitle,
            'custom_slug' => Str::slug($metaTitle),
            'meta_description' => $this->faker->sentence(rand(2, 5))
        ];
    }
}
