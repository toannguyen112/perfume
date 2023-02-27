<?php

namespace App\Http\Controllers\Backend\Blog;

use App\Models\Blog\PostCategory;
use Jamstackvietnam\Support\Traits\HasCrudActions;
use App\Http\Controllers\Controller;

class PostCategoryController extends Controller
{
    use HasCrudActions;

    public $folder = 'Blog/PostCategories';

    public $model = PostCategory::class;

    public $appends = [
        'index' => ['formatted_updated_at', 'formatted_created_at']
    ];
}
