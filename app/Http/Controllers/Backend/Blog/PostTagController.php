<?php

namespace App\Http\Controllers\Backend\Blog;

use App\Models\Tag;
use Jamstackvietnam\Support\Traits\HasCrudActions;
use App\Http\Controllers\Controller;

class PostTagController extends Controller
{
    use HasCrudActions;

    public $folder = 'Blog/Tags';

    public $model = Tag::class;

    public $appends = [
        'index' => ['formatted_updated_at', 'formatted_created_at']
    ];

    public function filter($query)
    {
        return $query->orWhere('type', $this->model::TYPE_POST);
    }
}
