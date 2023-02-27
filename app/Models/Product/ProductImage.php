<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Jamstackvietnam\Support\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jamstackvietnam\Support\Models\File;

class ProductImage extends BaseModel
{
    use SoftDeletes;

    protected $table = 'product_images';

    public $fillable = [
        'product_id',
        'variant_ids',
        'position',

        'image_id',
        'image_url',
        'image_name',

        'source_url',
    ];

    protected $casts = [
        'source_variant_ids' => 'array',
        'variant_ids' => 'array'
    ];

    protected $append = [
        'static_url'
    ];

    public static function booted()
    {
        static::deleting(function ($model) {
            $model->file->delete();
        });
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class, 'image_id', 'id');
    }

    public function transform()
    {
        return [
            'static_url' => static_url($this->image_url ?? $this->source_url),
            'variant_ids' => $this->variant_ids,
        ];
    }

    public function getStaticUrlAttribute()
    {
        return static_url($this->image_url) ?? $this->source_url;
    }
}
