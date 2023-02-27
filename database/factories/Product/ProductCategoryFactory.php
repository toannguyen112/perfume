<?php

namespace Database\Factories\Product;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product\ProductCategory;
use Illuminate\Support\Str;

class ProductCategoryFactory extends Factory
{
    /**
    * The name of the factory's corresponding model.
    *
    * @var  string
    */
    protected $model = ProductCategory::class;

    /**
    * Define the model's default state.
    *
    * @return  array
    */
    public function definition(): array
    {
        $name = $this->faker->name;
        $metaTitle = $this->faker->word;
        $parent_id = ProductCategory::inRandomOrder()->where('parent_id', 0)->first()->id;
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'icon' => $this->setIcon(),
            'content' => $this->faker->randomHtml(3, 10),
            'views' => rand(10, 1000),
            'parent_id' => $parent_id,

            'is_home'=> 0,
            'status'=> array_rand(ProductCategory::STATUS_LIST),

            'meta_title' => $metaTitle,
            'custom_slug' => Str::slug($metaTitle),
            'meta_description' => $this->faker->sentence(rand(2, 5)),
        ];
    }

    private function setIcon(){
        return '<svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M14.6683 21.2987C14.6683 21.9807 14.1152 22.5338 13.4333 22.5338C12.7513 22.5338 12.1982 21.9807 12.1982 21.2987V11.4185H14.6683V21.2987Z" fill="#39393A"/>
        <path d="M8.6987 21.2986C8.6987 21.9806 8.14563 22.5337 7.46367 22.5337C6.78189 22.5337 6.22864 21.9806 6.22864 21.2986V13.0649H8.6987V21.2986Z" fill="#39393A"/>
        <path d="M20.8434 21.2987C20.8434 21.9806 20.2903 22.5337 19.6083 22.5337C18.9264 22.5337 18.3733 21.9806 18.3733 21.2987V12.2415H20.8434V21.2987Z" fill="#39393A"/>
        <path d="M12.1983 10.1833L9.31641 4.47837C11.5904 2.2037 15.2781 2.20525 17.5501 4.47837L14.6682 10.1833H12.1983Z" fill="#39393A"/>
        <path d="M20.8435 11.0064H18.3734L17.5502 6.4782L21.6667 5.24316L20.8435 11.0064Z" fill="#39393A"/>
        <path d="M9.72817 8.53622C9.72817 10.355 8.7135 11.8295 7.46382 11.8295C6.21414 11.8295 5.19983 10.3548 5.19983 8.53622C5.19983 6.71764 6.21432 5.24292 7.46382 5.24292C8.7135 5.24292 9.72817 6.71764 9.72817 8.53622Z" fill="#39393A"/>
        </svg>';
    }
}
