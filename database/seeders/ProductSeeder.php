<?php

namespace Database\Seeders;

use App\Imports\ProductImport;
use App\Models\Product\Option;
use App\Models\Product\ProductVariant;
use App\Models\Product\ProductVariantRefOption;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class ProductSeeder extends Seeder
{
    public function run()
    {
        Excel::import(new ProductImport, database_path('master_data/products.xlsx'));

        $optionIds = Option::query()
                        ->where('name', 'Kích thước')
                        ->orWhere('name', 'Màu sắc')
                        ->pluck('id');

        ProductVariantRefOption::query()
            ->whereNull('product_variant_id')
            ->whereIn('option_id', $optionIds)
            ->update(['is_required' => true]);

        $variants = ProductVariant::query()->get()->unique('product_id');

        foreach ($variants as $variant) {
            $variant->update(['is_default' => true]);
        }
    }
}
