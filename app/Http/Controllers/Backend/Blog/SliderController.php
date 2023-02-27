<?php

namespace App\Http\Controllers\Backend\Blog;

use App\Models\Blog\Slider;
use Jamstackvietnam\Support\Traits\HasCrudActions;
use App\Http\Controllers\Controller;

class SliderController extends Controller
{
    use HasCrudActions;

    public $folder = 'Blog/Sliders';

    public $model = Slider::class;

    public $appends = [
        'index' => ['formatted_updated_at', 'formatted_created_at', 'formatted_position'],
        'form' => ['thumbnail', 'thumbnail_mobile']
    ];
}
