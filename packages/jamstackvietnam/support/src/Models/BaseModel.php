<?php

namespace Jamstackvietnam\Support\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jamstackvietnam\Support\Traits\ClearsResponseCache;

class BaseModel extends Model
{
    use ClearsResponseCache;

    public $fillable = [];

    /**
     * set string fields for filtering
     * @var array
     */
    protected $likeFilterFields = [];

    /**
     * set boolean fields for filtering
     * @var array
     */
    protected $boolFilterFields = [];

    public function scopeFilter(Builder $builder, array $filters = []): Builder
    {
        if (!$filters) {
            return $builder;
        }

        $tableName = $this->getTable();

        foreach ($filters as $field => $value) {
            if (in_array($field, $this->boolFilterFields) && $value != null) {
                $builder->where($field, (bool)$value);
                continue;
            }

            if (!in_array($field, $this->fillable) || !$value) {
                continue;
            }

            if (in_array($field, $this->likeFilterFields)) {
                $builder->where($tableName . '.' . $field, 'LIKE', "%$value%");
            } else if (is_array($value)) {
                $builder->whereIn($field, $value);
            } else {
                $builder->where($field, $value);
            }
        }

        return $builder;
    }

    public function scopeBuildData(Builder $builder)
    {
        if ($search = request()->input('search')) {
            $builder = $this->search($search);
        } else {
            $builder = $builder->orderBy('id', 'desc');
        }

        if (!method_exists($this, 'scopeActive')) {
            $builder = $builder->active();
        };

        if (request()->has('paginate') && !request()->input('paginate')) {
            $items = $builder->limit(request()->input('limit', 20))->get();
        } else {
            $items = $builder->paginate(request()->input('limit', 11))
                ->through(fn ($item) => $item->transform())
                ->withQueryString();
        }

        return $items;
    }

    public function getFormattedUpdatedAtAttribute(): string
    {
        if (!empty($this->updated_at)) {
            return datetime_format($this->updated_at);
        }
        return '';
    }

    public function getFormattedCreatedAtAttribute(): string
    {
        if (!empty($this->created_at)) {
            return datetime_format($this->created_at);
        }
        return '';
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', 1);
    }
}
