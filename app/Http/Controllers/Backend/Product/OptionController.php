<?php

namespace App\Http\Controllers\Backend\Product;

use App\Models\Product\Option;
use Jamstackvietnam\Support\Traits\HasCrudActions;
use App\Http\Controllers\Controller;

class OptionController extends Controller
{
    use HasCrudActions;

    public $model = Option::class;
    public $with = [
        'index' => ['children'],
        'form' => ['children', 'categories']
    ];
    public $appends = [
        'index' => ['formatted_updated_at', 'formatted_created_at'],
        'form' => []
    ];

    public function filter($query)
    {
        return $query->whereNull('parent_id')->orWhere('parent_id', 0);
    }
}
