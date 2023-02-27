<?php

namespace App\Models;

use Jamstackvietnam\Support\Models\BaseModel;

/**
 * @package App\Models
 */
class Seo extends BaseModel
{
    public $table = 'seo';

    public $fillable = [
        'meta_title',
        'meta_description',
        'content',
        'image_url',
        'author',

        'brand_id',
        'product_category_id',
        'option_ids',
    ];

    protected $casts = [
        'option_ids' => 'array'
    ];

    public function modelRules(): array
    {
        return [
            'all' => [
                'meta_title' => 'required|string|max:255',
            ],
        ];
    }

    public static function findSeoData($categoryId = null, $brandId = null, $optionIds = [])
    {
        $query = self::query();

        if ($categoryId) {
            $query = $query->where('product_category_id',  $categoryId);
        }
        if ($brandId) {
            $query = $query->where('brand_id',  $brandId);
        }
        if (!empty($optionIds)) {
            $query = $query->whereJsonContains('option_ids',  $optionIds);
        }

        $data = $query->first();

        return [
            'meta_title' => $data?->meta_title,
            'meta_description' => $data?->meta_description,
            'content' => transform_richtext($data?->content),
            'image_url' => $data?->image_url,
            'author' => $data?->author,

            'brand_id' => $brandId,
            'product_category_id' => $categoryId,
            'option_ids' => $optionIds
        ];
    }
}
