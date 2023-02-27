<?php

namespace App\Models\Blog;

use Jamstackvietnam\Support\Models\BaseModel;

class ProductRefTag extends BaseModel
{
    protected $table = 'post_tags';

    public $fillable = [
        'post_id',
        'tag_id',
    ];

}
