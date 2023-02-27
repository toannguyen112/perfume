<?php

namespace Jamstackvietnam\Support\Traits;

use Jamstackvietnam\Support\Models\MetaData;

trait HasMetaData
{
    public static function bootHasMetaData()
    {
        static::saved(function ($model) {
            $model->saveMetaData(request('meta_data', []));
        });
    }

    public function meta()
    {
        return $this->morphOne(MetaData::class, 'entity');
    }

    public function saveMetaData($data = [])
    {
        if (!empty($data)) {
            $this->meta()->delete();
            foreach ($data as $key => $value) {
                if (is_null($key) || is_null($value)) continue;
                $this->meta()->updateOrCreate([
                    'key' => $key,
                    'value' => $value,
                ]);
            }
        }
    }

    public function makeMetaData($key, $value)
    {
        return new MetaData([
            'key' => $key,
            'value' => $value,
        ]);
    }

    public function getMetaDataAttribute()
    {
        if (is_null($this->meta)) return null;
        return $this->meta->get()
            ->keyBy('key')->map(fn ($meta) => $meta->value);
    }
}
