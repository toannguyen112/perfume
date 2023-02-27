<?php

namespace App\Http\Controllers\Backend\Product;

use App\Models\Product\ProductCategory;
use Jamstackvietnam\Support\Traits\HasCrudActions;
use App\Http\Controllers\Controller;
use Inertia\Inertia;

class ProductCategoryController extends Controller
{
    use HasCrudActions;

    public $folder = 'Product/ProductCategories';

    public $model = ProductCategory::class;

    public $appends = [
        'index' => [
            'formatted_updated_at', 'formatted_created_at', 'tags',
            'thumbnail_left', 'thumbnail_center', 'thumbnail_right', 'link_header_left', 'link_header_right', 'link_header_center'
        ],
        'form' => ['tags']
    ];

    public function index()
    {
        $item = $this->initEmptyModel(new $this->model);

        return Inertia::render($this->folder() . '/Index', [
            'item' =>  $item,
            'rules' => $this->getModelRules('store')
        ]);
    }

    private function redirectToForm($id, $message)
    {
        $currentRoute =  request()->route()->getName();
        $currentRoutePaths = explode('.', $currentRoute);
        $currentRoutePaths[count($currentRoutePaths) - 1] = 'index';
        $formRoute = implode('.', $currentRoutePaths);

        return redirect(route($formRoute, ['id' => $id]))->with('success', $message);
    }
}
