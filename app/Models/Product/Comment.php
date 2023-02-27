<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Comment extends Model
{
    use HasFactory;

    const STATUS_ACTIVE = "ACTIVE";
    const STATUS_INACTIVE = "INACTIVE";

    public const STATUS_LIST = [
        self::STATUS_ACTIVE => "Hoạt động",
        self::STATUS_INACTIVE => 'Ẩn'
    ];

    public $fillable = [
        'name',
        'phone',
        'content',
        'status',
        'images',
        'rate_score',
        'product_id',
    ];

    protected $casts = [
        'images' => 'array'
    ];

    public function modelRules()
    {
        return [
            'comment' => [
                'name' => 'required|string|max:100',
                'content' => 'required|string|max:255',
                'rate_score' => 'required|int'
            ],
        ];
    }

    public function scopeFilter(Builder $query, array $filters = []): Builder
    {

        $query->when($filters['status'] ?? false, function (Builder $query, $value) {
            $query->active();
        });

        $query->when($filters['is_new'] ?? false, function (Builder $query, $value) {
            $query->orderBy('id', 'desc');
        });

        $query->when($filters['has_image'] ?? false, function (Builder $query, $value) {
            $query->whereJsonLength('images', '>', 0);
        });

        $query->when($filters['rate_score'] ?? false, function (Builder $query, $value) {
            $query->where('rate_score', '>=', $value);
        });

        return $query;
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function getFormattedCreatedAtAttribute(): string
    {
        return datetime_format($this->created_at, 'd/m/Y');
    }

    public function getFormattedUpdatedAtAttribute(): string
    {
        return datetime_format($this->updated_at, 'd/m/Y');
    }

    public function transform(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'content' => $this->content,
            'images' => collect($this->images)
                ->map(fn ($item) => static_url($item['image_url'])),
            'rate_score' => $this->rate_score,
            'formatted_created_at' => $this->formatted_created_at,
        ];
    }
}
