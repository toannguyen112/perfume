<?php

namespace App\Http\Controllers\Frontend\Common;

use App\Models\Region;
use App\Http\Controllers\Controller;
use Jamstackvietnam\Support\Traits\ApiResponse;

class RegionController extends Controller
{
    use ApiResponse;

    public $model = Region::class;

    public function index()
    {
        $query = $this->model::query();
        $query = $query->where('parent_code', request()->query('code'));

        $items = $query->orderBy('sort', 'desc')->orderBy('name_with_type')->get();
        $items = $items->map->only(['code', 'name_with_type']);

        return $this->success($items);
    }
}
