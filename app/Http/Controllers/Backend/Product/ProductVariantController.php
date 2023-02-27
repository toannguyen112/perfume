<?php

namespace App\Http\Controllers\Backend\Product;

use Jamstackvietnam\Support\Traits\HasCrudActions;
use App\Http\Controllers\Controller;
use App\Models\Product\ProductVariant;
use App\Models\Product\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ProductVariantController extends Controller
{
    use HasCrudActions;

    public $model = ProductVariant::class;

    public $folder = 'Product/Variants';

    public $with = [
        'index' => [],
        'form' => [],
    ];
    public $appends = [
        'index' => ['formatted_updated_at', 'formatted_created_at'],
        'form' => ['image_url']
    ];

    public function form($productId)
    {
        $item = new $this->model;

        if (request()->has('id') && !is_null(request()->input('id'))) {
            $item = $this->loadRelations($item);

            if (!is_null($item->getMacro('withTrashed'))) {
                $item = $item->withTrashed();
            }

            $item = $item->findOrFail(request()->input('id'));
            $item = $this->setAppends($item);
            $item = array_merge($this->initEmptyModel($item), $item->toArray());
        } else {
            $itemTranslation = $this->initTranslation($item);
            $item = array_merge($this->initEmptyModel($item), $itemTranslation);
        }

        if (request()->wantsJson()) {
            return $item;
        }

        $product = $this->getProduct($productId);

        return Inertia::render($this->folder() . '/Form', [
            'item' => $item,
            'product' => $product,
            'rules' => $this->getModelRules(__FUNCTION__)
        ]);
    }

    public function saveImages($model)
    {
        $model->saveImages($model);
    }

    public function edit($productId, $variantId)
    {
        $product = $this->getProduct($productId);

        $variant = ProductVariant::query()
            ->with('options')
            ->where('id', $variantId)
            ->firstOrFail()
            ->append('product_images')
            ->toArray();

        $variant['options'] = (object) collect($variant['options'])
            ->map(function ($item) {
                $item['id'] = $item['id'];
                return $item;
            })
            ->keyBy('parent_id')->toArray();

        return inertia($this->folder() . '/Form', [
            'product' => $product,
            'item' => $variant,
            'rules' => $this->getModelRules(__FUNCTION__)
        ]);
    }

    public function store(Request $request, $productId)
    {
        $this->validateRequest(__FUNCTION__);
        $is_update = $request->has('id') && !is_null($request->input('id'));
        $data = $request->all();

        if ($is_update) {
            $resource = $this->model::findOrFail(request()->input('id'));
            $resource->update($data);
        } else {
            $data['product_id'] = $productId;
            $resource = $this->model::create($data);
        }

        if (request()->wantsJson()) {
            return $resource;
        }

        return redirect(route('sidebar.variants.edit', [
            'productId' => $productId,
            'variantId' => $resource->id
        ]))
            ->with('success', 'Lưu đối tượng thành công.');
    }

    public function destroy()
    {
        try {
            DB::beginTransaction();
            $resource = $this->model::findOrFail(request()->input('id'));
            $resource->delete();
            DB::commit();

            return redirect(route('sidebar.variants.form', [
                'productId' => $resource->product_id,
            ]))->with('success', 'Xoá thành công.');

        } catch (\Exception $e) {
            DB::rollBack();
            if (env('APP_ENV') === 'local') throw $e;
            return back()->withError($e->getMessage());
        }
    }

    private function getProduct($productId){
        return Product::query()
            ->where('id', $productId)
            ->with('requiredProductOptions', 'variants.options')
            ->firstOrFail()
            ->append('list_variant_option');
    }
}
