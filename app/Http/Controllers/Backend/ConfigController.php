<?php

namespace App\Http\Controllers\Backend;

use App\Models\Config;
use Jamstackvietnam\Support\Traits\HasCrudActions;
use App\Http\Controllers\Controller;

class ConfigController extends Controller
{
    use HasCrudActions;

    public $model = Config::class;
    public $with = [
        'index' => [],
        'form' => []
    ];
    public $appends = [
        'form' => []
    ];
}
