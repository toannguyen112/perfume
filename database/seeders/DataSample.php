<?php

namespace Database\Seeders;

use App\Models\Product\Brand;
use App\Models\Product\Product;
use App\Models\Product\ProductCategory;
use Illuminate\Database\Seeder;

class DataSample extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductCategory::where('is_home', true)->update(['is_home' => false]);
        $categories = ProductCategory::withCount('products')->orderBy('products_count', 'DESC')->take(8)->get();
        foreach ($categories as $category) { $category->update(['is_home' => true]); }

        $categories = ProductCategory::where('name', 'LIKE', 'Quà tặng%')->where('parent_id', '<>', 0)->get();

        $products = Product::active()->get();
        foreach ($categories as $category) {
            $productIds = $products->random(10, 20)->pluck('id');
            $category->products()->sync($productIds);
            $category->parent->products()->syncWithoutDetaching($productIds);
        }

        $category = ProductCategory::where('name', 'Khuyễn mãi giày')->first();
        $productIds = Product::whereHas('categories', function ($query){
            $query->where('name', 'LIKE', '%giày%');
        })->get()->random(40, 60)->pluck('id');
        $category->products()->sync($productIds);
    }
}
