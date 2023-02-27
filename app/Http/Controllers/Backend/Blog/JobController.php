<?php

namespace App\Http\Controllers\Backend\Blog;

use App\Models\Blog\Job;
use Jamstackvietnam\Support\Traits\HasCrudActions;
use App\Http\Controllers\Controller;

class JobController extends Controller
{
    use HasCrudActions;

    public $model = Job::class;

    public $folder = 'Blog/Jobs';

    public $appends = [
        'index' => ['formatted_created_at', 'formatted_updated_at', 'formatted_expected_time']
    ];
}
