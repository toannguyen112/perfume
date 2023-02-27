<?php

namespace App\Imports;

use App\Models\Product\Brand;
use App\Models\Product\Option;
use App\Models\Product\Product;
use App\Models\Product\ProductVariant;
use App\Models\Product\ProductCategory;
use App\Models\Product\ProductImage;
use App\Models\Product\ProductVariantRefOption;
use App\Models\Tag;
use Jamstackvietnam\Support\Models\File;
use Jamstackvietnam\Support\Traits\UseExcel;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Row;

class ProductImport implements OnEachRow
{
    use UseExcel;

    protected $categories = [];
    protected $tags = [];
    protected $brands = [];
    protected $requiredOptionIds = [];

    protected $imageUrls = [];
    protected $currentRow = null;
    public function onRow(Row $row)
    {
        $rowIndex = $row->getIndex();
        $this->setup($row);

        $sourceSku = $this->column('SKU');

        // if ($rowIndex > 1 && $rowIndex < 10 && !empty($sourceSku)) {
        if ($rowIndex > 1 && !empty($sourceSku)) {
            dump("Row: $rowIndex - SKU: " . $sourceSku);

            $sourceProductId = $this->column('ID Sản phẩm');
            $sourceVariantId = $this->column('ID Biến thể');

            $categoryIds = $this->updateOrCreateCategories($this->column('Danh mục'));
            $tagIds = $this->updateOrCreateTags($this->column('Tags'));
            $files = $this->updateOrCreateFiles($this->column('Hình ảnh'));
            $brandId = $this->updateOrCreateBrand($this->column('Hãng'));

            $dataVariant = [
                'sku' => $sourceSku,
                'price' => $this->column('Giá'),
                'old_price' => $this->column('Giá so sánh'),
                'title' => trim($this->column('Tên sản phẩm')),
                'is_default' => 0,
                'source' => ProductVariant::SOURCE_SHEIN,
                'source_sku' => $sourceSku,
                'source_product_id' => $sourceProductId,
                'source_variant_id' => $sourceVariantId,
                'source_category_id' => $this->column('ID Danh mục'),
                'source_url' => $this->column('URL'),
                'source_raw' => $this->column('Raw')
            ];

            $raw =  json_decode($dataVariant['source_raw']);
            $sizeGuide = json_decode($this->column('SizeGuide'));
            $meta = json_decode($this->column('Meta'));

            $dataProduct = [
                'name' => trim($this->column('Tên sản phẩm')),
                'content' => $this->column('Mô tả'),
                'size_guide_table' => $this->transformSizeGuideTable($this->column('SizeList')),
                'size_guide_tutorial' => $this->transformSizeGuideTutorial($sizeGuide ? $sizeGuide?->description_multi : []),
                'size_guide_image' => $sizeGuide ? $sizeGuide?->image_url : null,
                'brand_id' => $brandId,
                'meta_title' => trim($meta?->meta_title),
                'meta_description' => $meta?->meta_description,
                'source' => ProductVariant::SOURCE_SHEIN,
                'source_id' => $sourceProductId,
            ];

            $sizeOptionIds = $this->transformSizeList($this->column('SizeList'));
            $sizeChildOptionIds = array_slice($sizeOptionIds, 1);

            $optionIds = collect($raw->productDetails)->groupBy('attr_name')->map(function ($options) {
                $name = trim(ucfirst($options->first()->attr_name));
                $parentId = Option::updateOrCreate(
                    ['name' => $name],
                    [
                        'name' => $name,
                        'status' => Option::STATUS_ACTIVE
                    ],
                )->id;

                $childItems = collect($options)->pluck('attr_value');
                foreach ($childItems as $childItem) {
                    $name = trim(ucfirst((string) $childItem));
                    $optionIds[] = Option::updateOrCreate(
                        ['name' => $name],
                        [
                            'name' => $name,
                            'parent_id' => $parentId,
                            'status' => Option::STATUS_ACTIVE
                        ],
                    )->id;
                }

                $optionIds[] = $parentId;

                return $optionIds;
            })->flatten()->toArray();

            $variants = ProductVariant::withTrashed()
                ->where('source', ProductVariant::SOURCE_SHEIN)
                ->where('source_sku', $sourceSku)
                ->get();

            if ($variants->count() === 0) {
                $product = Product::withTrashed()
                    ->where('source', ProductVariant::SOURCE_SHEIN)
                    ->where('source_id', $sourceProductId)
                    ->first();

                if (empty($product)) {
                    $product = Product::create($dataProduct);
                    $product->options()->detach();
                    $product->tags()->sync($tagIds);
                    $product->categories()->sync($categoryIds);
                }

                $variantIds = [];
                if (count($sizeOptionIds) > 0) {
                    foreach ($sizeChildOptionIds as $sizeOptionId) {
                        $variantIds[] = $product->variants()->create($dataVariant)->id;
                    }
                } else {
                    $variantIds[] = $product->variants()->create($dataVariant)->id;
                }
            } else {
                $product = $variants->first()->product;
                $product->update($dataProduct);
                $variantIds = $variants->pluck('id');
            }

            $productId = $product->id;
            $productFiles = collect($files)->map(function ($file) use ($productId, $variantIds) {
                return array_merge($file, [
                    'product_id' => $productId,
                    'variant_ids' => json_encode($variantIds)
                ]);
            })->toArray();

            ProductImage::insert($productFiles);

            $optionIds = array_merge($sizeOptionIds, $optionIds);
            $product->options()
                ->syncWithoutDetaching($optionIds);


            $colorId = $this->getCurrentColor($this->column('Raw'));

            if ($colorId) {
                $image = str_replace('//', 'https://', $this->column('Hình ảnh Màu sắc'));
                Option::find($colorId)->update(['image_url' => $image]);
            }

            foreach ($variantIds as $index => $variantId) {
                if ($colorId) {
                    ProductVariantRefOption::query()
                        ->updateOrCreate(
                            [
                                'option_id' => $colorId,
                                'product_id' => $product->id,
                                'product_variant_id' => $variantId,
                            ],
                            []
                        );
                }

                if (count($sizeChildOptionIds) > 0 && isset($sizeChildOptionIds[$index])) {
                    ProductVariantRefOption::query()
                        ->updateOrCreate(
                            [
                                'option_id' => $sizeChildOptionIds[$index],
                                'product_id' => $product->id,
                                'product_variant_id' => $variantId,
                            ],
                            []
                        );
                }
            }
        }
    }

    private function updateOrCreateTags($names)
    {
        $names = explode(',', $names);
        $tags = [];
        foreach ($names as $name) {
            if (!empty($name)) {
                if (isset($this->tags[$name])) {
                    $tagId = $this->tags[$name];
                } else {
                    $tagId = Tag::create([
                        'type' => Tag::TYPE_PRODUCT,
                        'name' => trim($name),
                    ])->id;
                    $this->tags[$name] = $tagId;
                }

                $tags[] = $tagId;
            }
        }

        return $tags;
    }

    private function updateOrCreateCategories($names)
    {
        $names = explode(',', $names);
        $parentId = null;
        $categories = [];
        foreach ($names as $name) {
            if (!empty($name)) {
                $name = trim($name);
                if (isset($this->categories[$name])) {
                    $categoryId = $this->categories[$name];
                } else {
                    $categoryId = ProductCategory::create([
                        'type' => ProductCategory::TYPE_PRODUCT,
                        'name' => $name,
                        'parent_id' => $parentId,
                        'status' => ProductCategory::STATUS_ACTIVE
                    ])->id;
                    $this->categories[$name] = $categoryId;
                }

                $categories[] = $categoryId;
                $parentId = $categoryId;
            }
        }

        return $categories;
    }

    private function updateOrCreateFiles($imageUrls)
    {
        $fileUrls = collect(explode(',', $imageUrls))
            ->map(fn ($item) => str_replace('//', 'https://', $item))
            ->toArray();

        return File::storeFileFromUrls($fileUrls, (object) ['isStoreFile' => false])->map(fn ($item) => [
            'source_url' => $item->source_url,
            'image_id' => $item->id,
            'image_url' => $item->path,
        ]);
    }

    private function updateOrCreateBrand($name)
    {
        if (!empty($name)) {
            return null;
        }

        if (isset($this->brands[$name])) {
            $brandId = $this->brands[$name];
        } else {
            $brandId = Brand::updateOrCreate(['name' => $name])->id;
            $this->brands[$name] = $brandId;
        }
    }

    private function transformSizeGuideTutorial($items)
    {
        $html = '';
        foreach ($items as $item) {
            $html .= "<div><h6>$item->name</h6><p>$item->description</p></div>";
        }
        return $html;
    }

    private function transformSizeList($items): array
    {
        try {
            $items = json_decode($items);
            $attributeName = $items[0]->attr_name;
            $attributes = array_column($items[0]->attr_value_list, 'attr_value_name');

            $optionIds = [];
            $optionId = Option::updateOrCreate([
                'name' => trim(ucfirst($attributeName))
            ])->id;
            $optionIds[] = $optionId;

            foreach ($attributes as $attribute) {
                $optionIds[] = Option::updateOrCreate([
                    'name' => trim(ucfirst($attribute)),
                    'parent_id' => $optionId,
                ])->id;
            }
            return $optionIds;
        } catch (\Exception $e) {
            return [];
        }
    }

    private function transformSizeGuideTable($items)
    {
        try {
            $html = '';
            $items = json_decode($items);
            $rows = $items[0]->attr_value_list;
            $attributeName = $items[0]->attr_name;

            $columns = collect($rows[0]->size_desc[0]->list)
                ->pluck('k')
                ->join(fn ($item) => "<td>$item</td>");

            $rows = collect($rows)->map(function ($row) {
                $cells = collect($row->size_desc[0]->list)
                    ->pluck('val')
                    ->join(fn ($item) => "<td>$item</td>");
                return "<tr><td>$row->attr_value_name</td>$cells</tr>";
            })->join('');

            $html = "<table><thead><tr><td>$attributeName</td>$columns</tr></thead><tbody>$rows</tbody></table>";
            return $html;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function getCurrentColor($raw)
    {
        try {
            $raw = json_decode($raw);
            $name = $raw->mainSaleAttribute[0]->attr_value;
            return Option::where('name', trim(ucfirst($name)))->first()->id;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function setup($row)
    {
        $this->currentRow = $row->toArray();
        if ($row->getIndex() > 1) return;

        $this->setColumns($row);

        $this->categories = ProductCategory::withTrashed()->get()->keyBy('name')->map(fn ($item) => $item->id);
        $this->tags = Tag::withTrashed()->get()->keyBy('name')->map(fn ($item) => $item->id);
        $this->brands = Brand::withTrashed()->get()->keyBy('name')->map(fn ($item) => $item->id);

        $this->requiredOptionIds[] = Option::create(['name' => 'Màu sắc', 'status' => Option::STATUS_ACTIVE])->id;
        $this->requiredOptionIds[] = Option::create(['name' => 'Kích thước', 'status' => Option::STATUS_ACTIVE])->id;
    }
}
