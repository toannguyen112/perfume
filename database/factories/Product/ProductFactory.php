<?php

namespace Database\Factories\Product;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product\Product;
use App\Models\Product\Brand;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var  string
    */
    protected $model = Product::class;

    /**
    * Define the model's default state.
    *
    * @return  array
    */
    public function definition(): array
    {
        $brand_id = Brand::inRandomOrder()->limit(1)->first()->id;

        $name = $this->faker->name;
        $metaTitle = $this->faker->word;
        return [
            'slug' => $name,
            'name' => Str::slug($name),
            'content' => $this->faker->randomHtml(3, 10),
            'views' => rand(10, 1000),

            'is_hot'=> rand(0, 1),
            'is_sale'=> rand(0, 1),
            'is_new'=> rand(0, 1),
            'is_limited'=> rand(0, 1),
            'brand_id' => $brand_id,

            'status'=> array_rand(Product::STATUS_LIST),

            'meta_title' => $metaTitle,
            'custom_slug' => Str::slug($metaTitle),
            'meta_description' => $this->faker->sentence(rand(2, 5)),
        ];
    }
}
