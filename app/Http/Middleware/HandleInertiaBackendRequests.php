<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use App\Models\Contact\Contact;
use App\Models\Product\Order;
use App\Models\Product\Comment;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaBackendRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'backend';

    /**
     * Determine the current asset version.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request)
    {
        $user = auth()->guard('admin')->user();

        $data = array_merge(parent::share($request), [
            'env' => [
                'static_url' => env('STATIC_URL'),
            ],
            'new_order_count' => Order::where('status', Order::STATUS_NEW)->count(),
            'new_contact_count' => Contact::where('status', Contact::STATUS_NEW)->count(),
            'new_comment_count' => Comment::where('status', Comment::STATUS_INACTIVE)->count(),
            'auth' => [
                'user' => $user,
                'permissions' => $user ? $user->getAllPermissions()->pluck('name') : []
            ],
            'config' => [
                'locales' => config('translatable.locales'),
            ],
            'flash' => function () use ($request) {
                return [
                    'success' => $request->session()->get('success'),
                    'error' => $request->session()->get('error'),
                ];
            },
        ]);

        return $data;
    }
}
