<?php

namespace App\Http\Controllers\Backend\Product;

use App\Models\Product\ProductCategory;
use Jamstackvietnam\Support\Traits\HasCrudActions;
use App\Http\Controllers\Controller;

class UseCaseController extends Controller
{
    use HasCrudActions;

    public $model = ProductCategory::class;

    public $folder = 'UseCases';

    protected $appends = [
        'index' => ['thumbnail_path', 'formatted_start_day', 'formatted_end_day'],
        'form' => ['thumbnail'],
    ];

    public function filter($query)
    {
        return $query->where('type', ProductCategory::TYPE_USE_CASE);
    }
}
