<?php

namespace App\Models\Product;

use Jamstackvietnam\Support\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderLine extends BaseModel
{
    protected $table = 'order_lines';

    public $fillable = [
        'id',
        'is_combo',
        'combo_id',
        'product_id',
        'order_id',
        'source',
        'source_id',
        'variant_id',
        'title',
        'name',
        'quantity',
        'price',
        'total_discount',
        'grams',
        'sku',
        'variant_title',
        'variant_inventory_management',
        'vendor',
        'fulfillment_service',
        'requires_shipping',
        'taxable',
        'gift_card',
        'product_exists',
        'properties',
        'tax_lines',
        'fulfillment_status',
        'fulfillable_quantity',
    ];

    protected $hidden = [];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'closed_at' => 'datetime',
        'total_discount' => 'int',
        'price' => 'int',
    ];

    protected $with = ['product'];

    protected $dates = [
        'created_at',
        'updated_at',
        'closed_at',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function productVariant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id', 'id');
    }

    public function transform(): array
    {
        $options = [];
        $optionIds = ProductVariant::with('options')->findOrFail($this->variant_id)->transform()['options'];

        $options = $this->product->transformDetails()['options']
            ->map(
                function ($item) use ($optionIds) {
                    return [
                        "id" => $item['id'],
                        "name" => $item['name'],
                        "slug" => $item['slug'],
                        'nodes' => $item['nodes']->filter(fn ($x) => in_array($x['id'], $optionIds))->pluck('name')->toArray()
                    ];
                }
            );

        return [
            'id' => $this->id,
            'image_url' => $this->product->image_url ?? '',
            'product_id' => $this->product_id,
            'order_id' => $this->order_id,
            'source' => $this->source,
            'source_id' => $this->source_id,
            'variant_id' => $this->variant_id,
            'options' => $options,
            'title' => $this->title,
            'slug' => $this->product->slug ?? '',
            'name' => $this->name,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'total_discount' => $this->total_discount,
            'grams' => $this->grams,
            'sku' => $this->sku,
            'variant_title' => $this->variant_title,
        ];
    }
}
