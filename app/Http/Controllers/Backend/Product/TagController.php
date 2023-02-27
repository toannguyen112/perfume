<?php

namespace App\Http\Controllers\Backend\Product;

use App\Models\Tag;
use Jamstackvietnam\Support\Traits\HasCrudActions;
use App\Http\Controllers\Controller;

class TagController extends Controller
{
    use HasCrudActions;

    public $folder = 'Product/Tags';

    public $model = Tag::class;

    public $appends = [
        'index' => ['formatted_updated_at', 'formatted_created_at', 'formatted_type'],
    ];

    public function filter($query)
    {
        return $query->orderBy('type', 'desc')
            ->orderBy('id', 'desc');;
    }
}
