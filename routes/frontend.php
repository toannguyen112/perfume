<?php

use App\Http\Controllers\Frontend\Product\ProductCategoryController;
use App\Http\Controllers\Frontend\Blog\PolicyController;
use App\Http\Controllers\Frontend\Blog\PostController;
use App\Http\Controllers\Frontend\Blog\JobController;
use App\Http\Controllers\Frontend\Contact\ContactController;
use App\Http\Controllers\Frontend\Product\ProductController;
use App\Http\Controllers\Frontend\Product\SaleController;
use App\Http\Controllers\Frontend\Product\BrandController;
use App\Http\Controllers\Frontend\Product\OrderController;
use App\Http\Controllers\Frontend\Common\RegionController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\Product\CartController;
use Jamstackvietnam\Support\Controllers\MediaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\SitemapController;

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('sitemap.xml', [SitemapController::class, 'index']);

Route::get('/gioi-thieu', fn () => inertia('About'))->name('about');

Route::get('static/{staticId}/{year}/{month}/{day}/{filename}', [MediaController::class, 'cache']);

Route::get('/categories', fn () => inertia('Categories/Index'))->name('category.index');

Route::get('{categorySlug}-c-{categoryId}', [ProductCategoryController::class, 'show'])
    ->where('categorySlug', '.*?')
    ->where('categoryId', '\d+')
    ->name('categories.show');

Route::controller(BrandController::class)->group(function () {
    Route::get('/thuong-hieu', 'index')->name('brand.index');
    Route::get('/thuong-hieu/{slug}', 'show')->name('brand.show');
});

Route::controller(ProductController::class)->group(function () {
    Route::get('/new', 'new')->name('new.index');
    Route::get('/tim-kiem', 'search')->name('product.search');
    Route::get('/instant-search', 'instantSearch')->name('instant-search');

    Route::get('{productSlug}-p-{productId}', 'show')
        ->where('productSlug', '.*?')
        ->where('productId', '\d+')
        ->name('product.show');

    Route::get('products/{slug}-p-{id}/comments', 'getCommentsProduct')->name('product.comment.show')
        ->where('slug', '.*?')
        ->where('id', '\d+');

    Route::post('products/{slug}-p-{id}/comments', 'comment')->name('product.comment.store')
        ->where('slug', '.*?')
        ->where('id', '\d+');
});

Route::get('/lien-he', fn () => inertia('Contact'))->name('contact');
Route::post('lien-he', [ContactController::class, 'store'])->name('contact.store');
Route::post('job/form', [ContactController::class, 'store'])->name('job-form');

Route::controller(JobController::class)->group(function () {
    Route::get('/tuyen-dung', 'index')->name('job.index');
    Route::get('/tuyen-dung/{slug}', 'show')->name('job.show');
});

Route::controller(SaleController::class)->group(function () {
    Route::get('/khuyen-mai', 'index')->name('sale.index');
    Route::get('/khuyen-mai/{saleSlug}-s-{saleId}', 'show')
        ->where('saleSlug', '.*?')
        ->where('saleId', '\d+')
        ->name('sale.show');
});

Route::controller(PostController::class)->group(function () {
    Route::get('bai-viet', 'index')->name('post.index');
    Route::get('/bai-viet/{categorySlug}', 'categoryShow')->name('post.category-show');
    Route::get('/bai-viet/{categorySlug}/{slug}', 'show')->name('post.show');
});

Route::get('chinh-sach', [PolicyController::class, 'index'])->name('policy.index');
Route::get('chinh-sach/{slug}', [PolicyController::class, 'show'])->name('policy.show');

Route::prefix('checkout')->name('checkout.')->group(function () {

    Route::get('cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('cart/{rowId}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('cart/{rowId}', [CartController::class, 'destroy'])->name('cart.destroy');

    Route::get('payment', [OrderController::class, 'index'])->name('order.index');
    Route::get('payment/tracking/{orderNumber}', [OrderController::class, 'tracking'])->name('payment.tracking');
    Route::post('payment/checkout', [OrderController::class, 'checkout'])->name('payment.checkout');
});

Route::get('/404', fn () => inertia('Error'));

Route::get('regions', [RegionController::class, 'index'])->name('regions');

Route::get('route-filters', [ProductController::class, 'routeFilters'])
    ->name('route-filters');

Route::get('{slug}', [ProductController::class, 'index'])
    ->where('slug', '.*')
    ->name('products.index');
