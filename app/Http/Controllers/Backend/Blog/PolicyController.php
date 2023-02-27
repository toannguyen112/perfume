<?php

namespace App\Http\Controllers\Backend\Blog;

use App\Models\Blog\Post;
use Jamstackvietnam\Support\Traits\HasCrudActions;
use App\Http\Controllers\Controller;

class PolicyController extends Controller
{
    use HasCrudActions;

    public $model = Post::class;

    public $folder = 'Blog/Policies';

    public $appends = [
        'index' => ['formatted_updated_at', 'formatted_created_at']
    ];

    public function filter($query)
    {
        return $query->orWhere('type', $this->model::TYPE_POLICY);
    }
}
