<?php

namespace App\Models\Product;

use Jamstackvietnam\Support\Models\BaseModel;

/**
 * Class ProductRefView
 */
class ProductRefView extends BaseModel
{
    protected $table = 'product_ref_views';

    public $fillable = [
        'user_id',
        'product_variant_id',
        'product_id',
    ];

    protected $dates = ['created_at', 'updated_at'];
}
