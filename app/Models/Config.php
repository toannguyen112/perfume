<?php

namespace App\Models;

use Jamstackvietnam\Support\Models\BaseModel;

class Config extends BaseModel
{
    public $timestamps = false;

    protected $table = 'configs';

    public $fillable = [
        'id',
        'name',
        'value',
        'creator_id',
        'editor_id',
    ];

    public function modelRules()
    {
        return [
            'all' => [
                'name' => 'required|string|max:255',
                'value' => 'required|string',
            ],
        ];
    }
}
