<?php

namespace App\Http\Controllers\Backend\Product;

use App\Models\Product\Product;
use Jamstackvietnam\Support\Traits\HasCrudActions;
use App\Http\Controllers\Controller;
use Inertia\Inertia;

class ProductController extends Controller
{
    use HasCrudActions;

    public $folder = 'Product/Products';

    public $model = Product::class;

    public $with = [
        'index' => ['categories'],
        'form' => [
            'categories', 'variants.options', 'tags',
        ]
    ];

    public $without = [
        'index' => ['variants.options', 'options.parent'],
    ];

    public $appends = [
        'index' => ['formatted_updated_at', 'formatted_created_at'],
        'form' => ['sale_categories', 'use_case_categories', 'list_option']
    ];

    public function create()
    {
        return $this->form();
    }

    public function form($id = null)
    {
        $item = new $this->model;

        if (!empty($id)) {
            $item = $this->loadRelations($item);

            if (!is_null($item->getMacro('withTrashed'))) {
                $item = $item->withTrashed();
            }

            $item = $item->where('id', $id)->firstOrFail();
            $item = $this->setAppends($item);
            $item = array_merge($this->initEmptyModel($item), $item->toArray());
            $item = $this->transformDetails($item);
        } else {
            $itemTranslation = $this->initTranslation($item);
            $item = array_merge($this->initEmptyModel($item), $itemTranslation);
        }
        if (request()->wantsJson()) {
            return $item;
        }

        return Inertia::render($this->folder() . '/Form', [
            'item' => $item,
            'rules' => $this->getModelRules(__FUNCTION__)
        ]);
    }

    public function filter($query)
    {
        if (request()->input('statuses')) {
            $active = (bool) request()->input('statuses.active');
            if ($active) {
                $query = $query->orWhere('products.status', $this->model::STATUS_ACTIVE);
            } else {
                $query = $query->where('products.status', '<>', $this->model::STATUS_ACTIVE);
            }

            $disable = (bool) request()->input('statuses.disable');
            if ($disable) {
                $query = $query->orWhere('products.status', $this->model::STATUS_DISABLE);
            } else {
                $query = $query->where('products.status', '<>', $this->model::STATUS_DISABLE);
            }

            if ((bool) request()->input('statuses.source_is_merge')) {
                $query = $query->where('products.source_is_merge', true);
            }

            if ((bool) request()->input('statuses.deleted')) {
                $query = $query->withTrashed()
                    ->orWhereNotNull('products.status')
                    ->whereNotNull('products.deleted_at');
            }
        }

        $filters = array_merge(request()->only('category_ids'), ['filter_price' => false]);

        return $query->filter($filters);
    }

    public function transformDetails($item)
    {

        $item['variants'] = (object) collect($item['variants'])->map(function ($variant) {
            $variant['options'] = (object) collect($variant['options'])->keyBy('id');
            return $variant;
        });

        return $item;
    }
}
