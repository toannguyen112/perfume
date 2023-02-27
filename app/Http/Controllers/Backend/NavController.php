<?php

namespace App\Http\Controllers\Backend;

use App\Models\Nav;
use Jamstackvietnam\Support\Traits\HasCrudActions;
use App\Http\Controllers\Controller;
use Inertia\Inertia;


class NavController extends Controller
{
    use HasCrudActions;

    public $model = Nav::class;
    public $redirectBack = true;

    public $with = [
        'index' => [],
        'form' => []
    ];
    public $appends = [
        'index' => ['formatted_updated_at', 'formatted_created_at'],
        'form' => []
    ];

    public function index()
    {
        $item = $this->initEmptyModel(new $this->model);

        return Inertia::render($this->folder() . '/Index', [
            'item' => $item,
            'rules' => $this->getModelRules('store')
        ]);
    }
}
