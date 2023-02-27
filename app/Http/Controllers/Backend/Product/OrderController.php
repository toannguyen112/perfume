<?php

namespace App\Http\Controllers\Backend\Product;

use App\Models\Product\Order;
use Jamstackvietnam\Support\Traits\HasCrudActions;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    use HasCrudActions;

    public $model = Order::class;
    public $with = [
        'index' => [],
        'form' => ['orderLines']
    ];
    public $appends = [
        'index' => ['formatted_updated_at', 'formatted_created_at'],
        'form' => ['formatted_updated_at', 'formatted_created_at']
    ];
}
