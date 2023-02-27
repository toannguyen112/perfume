<?php

namespace App\Http\Controllers\Frontend\Blog;

use App\Models\Blog\Job;
use Inertia\Inertia;
use App\Http\Controllers\Frontend\BaseController;
use Jamstackvietnam\Support\Traits\ApiResponse;
use Jamstackvietnam\Support\Traits\HasApiCrudActions;

class JobController extends BaseController
{

    use HasApiCrudActions, ApiResponse;

    public $model = Job::class;

    public function index()
    {
        $query = $this->model::query()
            ->active()
            ->where('expected_time', '>=', now());

        $jobs = $query
            ->orderBy('id', 'DESC')
            ->paginate(5)
            ->through(function ($item) {
                return $item->transform();
            });

        return Inertia::render('Blog/Job/Index', [
            'jobs' => $jobs
        ]);
    }

    public function show($slug)
    {
        $item = Job::query()
            ->active()
            ->where(function ($query) use ($slug) {
                return $query->whereSlug($slug)->orWhere('custom_slug', $slug);
            })
            ->where('expected_time', '>=', now())
            ->firstOrFail()
            ->transformDetails();

        $jobOther = Job::active()
            ->where('expected_time', '>=', now())
            ->where('id', '<>', $item['id'])
            ->orderBy('id', 'DESC')
            ->paginate(5)
            ->through(function ($item) {
                return $item->transform();
            });

        return Inertia::render('Blog/Job/Show', [
            'item' => $item,
            'jobOther' => $jobOther
        ]);
    }
}
