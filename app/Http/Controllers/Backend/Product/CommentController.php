<?php

namespace App\Http\Controllers\Backend\Product;

use Jamstackvietnam\Support\Traits\HasCrudActions;
use App\Http\Controllers\Controller;
use App\Models\Product\Comment;

class CommentController extends Controller
{
    use HasCrudActions;

    public $folder = 'Product/Comments';

    public $model = Comment::class;

    public $appends = [
        'index' => ['formatted_updated_at', 'formatted_created_at'],
        'form' => ['formatted_updated_at', 'formatted_created_at'],
    ];
}
