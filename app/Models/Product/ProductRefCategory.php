<?php

namespace App\Models\Product;

use Jamstackvietnam\Support\Models\BaseModel;

class ProductRefCategory extends BaseModel
{
    protected $table = 'product_ref_categories';

    public $fillable = [
        'product_id',
        'product_category_id',
    ];

}
