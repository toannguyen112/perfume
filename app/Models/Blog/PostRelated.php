<?php

namespace App\Models\Blog;

use Jamstackvietnam\Support\Models\BaseModel;

class PostRelated extends BaseModel
{
    protected $table = 'post_related';

    public $fillable = [
        'post_id',
        'related_post_id',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class, 'related_post_id');
    }
}
