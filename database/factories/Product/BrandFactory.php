<?php

namespace Database\Factories\Product;

use App\Models\Product\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BrandFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Brand::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->name;
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->text,
            'status' => array_rand(Brand::STATUS_LIST),
            'is_hot' => rand(0, 1)
        ];
    }
}
