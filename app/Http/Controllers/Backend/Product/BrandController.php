<?php

namespace App\Http\Controllers\Backend\Product;

use App\Models\Product\Brand;
use Jamstackvietnam\Support\Traits\HasCrudActions;
use App\Http\Controllers\Controller;

class BrandController extends Controller
{
    use HasCrudActions;

    public $folder = 'Product/Brands';

    public $model = Brand::class;

    public $with = [];

    public $appends = [
        'index' => ['formatted_updated_at', 'formatted_created_at'],
        'form' => ['thumbnail']
    ];
}
