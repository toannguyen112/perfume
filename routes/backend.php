<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\Blog\PostController;
use App\Http\Controllers\Backend\Blog\PolicyController;
use App\Http\Controllers\Backend\Blog\PostCategoryController;
use App\Http\Controllers\Backend\Contact\ContactController;
use App\Http\Controllers\Backend\Contact\ApplyController;
use App\Http\Controllers\Backend\Blog\JobController;
use App\Http\Controllers\Backend\Blog\SliderController;
use App\Http\Controllers\Backend\Product\ProductController;
use App\Http\Controllers\Backend\Product\ProductCategoryController;
use App\Http\Controllers\Backend\Product\TagController;
use App\Http\Controllers\Backend\Product\SaleController;
use App\Http\Controllers\Backend\Product\UseCaseController;
use App\Http\Controllers\Backend\Product\OptionController;
use App\Http\Controllers\Backend\Product\ProductVariantController;
use App\Http\Controllers\Backend\Product\CommentController;
use App\Http\Controllers\Backend\Product\OrderController;
use App\Http\Controllers\Backend\Product\BrandController;
use App\Http\Controllers\Backend\ConfigController;
use App\Http\Controllers\Backend\NavController;
use App\Http\Controllers\Backend\SeoController;
use Jamstackvietnam\Support\Controllers\MediaController;
use Jamstackvietnam\Support\Controllers\HelperController;

Route::get('/', function () {
    return redirect('/admin/dashboard');
});

Route::middleware(['auth:admin', 'permission'])->group(function () {
    Route::name('sidebar.')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

        Route::module(UserController::class);
        Route::module(MediaController::class, ['only' => ['index', 'form', 'store', 'destroy']]);

        Route::module(AdminController::class);
        Route::module(RoleController::class);
        Route::module(PostController::class);
        Route::module(PolicyController::class);
        Route::module(ProductCategoryController::class);
        Route::module(PostCategoryController::class);
        Route::module(ContactController::class);
        Route::module(ApplyController::class);
        Route::module(SliderController::class);
        Route::module(TagController::class);
        Route::module(SaleController::class);
        Route::module(UseCaseController::class);
        Route::module(BrandController::class);
        Route::module(JobController::class);
        Route::module(OptionController::class);
        Route::module(CommentController::class);
        Route::module(OrderController::class);
        Route::module(NavController::class);
        Route::get('seo', [SeoController::class, 'index'])->name('seo.index');
        Route::get('seo/form', [SeoController::class, 'form'])->name('seo.form');
        Route::post('seo/store', [SeoController::class, 'store'])->name('seo.store');

        Route::module(ConfigController::class, ['only' => ['index', 'form', 'store']]);

        Route::module(ProductController::class, ['except' => 'form']);
        Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
        Route::get('products/{id}', [ProductController::class, 'form'])->name('products.form');
        Route::get('products/default/{variantId}', [ProductController::class, 'default'])->name('products.default');

        Route::get('products/{productId}/variants', [ProductVariantController::class, 'form'])
            ->name('variants.form')->withoutMiddleware('permission');
        Route::get('products/{productId}/variants/{variantId}', [ProductVariantController::class, 'edit'])
            ->name('variants.edit')->withoutMiddleware('permission');
        Route::post('products/{productId}/variants', [ProductVariantController::class, 'store'])
            ->name('variants.store')->withoutMiddleware('permission');
        Route::post('variants', [ProductVariantController::class, 'destroy'])
            ->name('variants.destroy')->withoutMiddleware('permission');
    });

    Route::post('model-data', [HelperController::class, 'getModelData'])->name('helper.model-data');
    Route::get('logs', [HelperController::class, 'getLogs'])->name('helper.logs');
});

require __DIR__ . '/auth.php';
