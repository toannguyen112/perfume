<?php

namespace App\Models\Product;

use Jamstackvietnam\Support\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariantRefOption extends BaseModel
{
    public $timestamps = false;
    public $incrementing = false;
    public $primaryKey = null;

    protected $table = 'product_variant_ref_options';

    public $fillable = [
        'option_id',
        'product_id',
        'product_variant_id',
        'is_required',
        'position',
    ];

    public function option(): BelongsTo
    {
        return $this->belongsTo(Option::class);
    }

    public static function storeProductOption()
    {
        $optionRefs = ProductVariantRefOption::query()->get();
        foreach ($optionRefs as $optionRef) {
            if (!empty($optionRef->option)) {
                $data = [
                    'option_id' => $optionRef->option->parent_id,
                    'product_id' => $optionRef->product_id,
                    'product_variant_id' => NULL,
                ];

                ProductVariantRefOption::updateOrCreate($data, array_merge($data, [
                    'is_required' => true
                ]));
            }
        }
    }
}
