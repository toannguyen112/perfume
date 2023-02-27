<?php

namespace App\Http\Controllers\Backend\Blog;

use App\Models\Blog\Post;
use Jamstackvietnam\Support\Traits\HasCrudActions;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    use HasCrudActions;

    public $folder = 'Blog/Posts';

    public $model = Post::class;

    public $with = [];

    public $appends = [
        'index' => ['formatted_updated_at', 'formatted_created_at', 'category_name', 'thumbnail_path'],
        'form' => ['post_related_ids', 'tags', 'thumbnail', 'banner_thumbnail']
    ];

    public function filter($query)
    {
        return $query->orWhere('type', $this->model::TYPE_POST);
    }
}
