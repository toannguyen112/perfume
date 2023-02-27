<?php

namespace App\Models\Blog;

use Jamstackvietnam\Support\Models\BaseModel;

class PostRefCategory extends BaseModel
{
    protected $table = 'post_ref_categories';

    public $fillable = [
        'post_id',
        'post_category_id',
    ];
}
