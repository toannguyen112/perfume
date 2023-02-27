<?php

namespace App\Models\Product;

use Jamstackvietnam\Support\Models\BaseModel;

class ProductRefTag extends BaseModel
{
    protected $table = 'product_tags';

    public $fillable = [
        'product_id',
        'tag_id',
    ];

}
