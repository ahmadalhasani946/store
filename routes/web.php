<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\VariantController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\BasketController;

Route::get('/', function () {return view('home.index');});

Auth::routes();
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/products/{category?}', [HomeController::class, 'products'])->name('products');
Route::get('/product/{product}', [HomeController::class, 'product'])->name('product');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/profile', [HomeController::class, 'profile'])->name('profile')->middleware('auth');
Route::get('/countOfProducts', [HomeController::class, 'getCountOfProductsInBasket'])->name('countOfProducts');
Route::get('/basket', [HomeController::class, 'getBasketContent'])->name('basket')->middleware('auth');
Route::get('/checkout', [HomeController::class, 'checkOut'])->name('checkout')->middleware('auth');
Route::post('/confirmorder', [HomeController::class, 'confirmOrder'])->name('confirmOrder')->middleware('auth');


Route::group(['prefix'=>'cp', 'middleware' => 'admin'],function () {
    Route::get('/', function () {return view('home');})->middleware(['auth']);


    Route::delete('/users/trashed/{user}', [UserController::class, 'forceDelete'])->name('users.forceDelete');
    Route::get('/users/{user}/restore', [UserController::class, 'restoreUser'])->name('users.restore');
    Route::resource('users', UserController::class);


    Route::delete('/options/trashed/{option}', [OptionController::class, 'forceDelete'])->name('options.forceDelete');
    Route::get('/options/{option}/restore', [OptionController::class, 'restoreOption'])->name('options.restore');
    Route::resource('options', OptionController::class);


    Route::delete('/variants/trashed/{variant}', [VariantController::class, 'forceDelete'])->name('variants.forceDelete');
    Route::get('/variants/{variant}/restore', [VariantController::class, 'restoreVariant'])->name('variants.restore');
    Route::resource('variants', VariantController::class);


    Route::delete('/categories/trashed/{category}', [CategoryController::class, 'forceDelete'])->name('categories.forceDelete');
    Route::get('/categories/{category}/restore', [CategoryController::class, 'restoreCategory'])->name('categories.restore');
    Route::resource('categories', CategoryController::class);


    Route::delete('/products/trashed/{product}', [ProductController::class, 'forceDelete'])->name('products.forceDelete');
    Route::get('/products/{product}/restore', [ProductController::class, 'restoreProduct'])->name('products.restore');
    Route::resource('products', ProductController::class);


    Route::delete('/reviews/trashed/{review}', [ReviewController::class, 'forceDelete'])->name('reviews.forceDelete');
    Route::get('/reviews/{review}/restore', [ReviewController::class, 'restoreReview'])->name('reviews.restore');
    Route::resource('reviews', ReviewController::class);


    Route::delete('/orders/trashed/{order}', [OrderController::class, 'forceDelete'])->name('orders.forceDelete');
    Route::get('/orders/{order}/restore', [OrderController::class, 'RestoreOrder'])->name('orders.restore');
    Route::post('/orders/getOptionVariant', [OrderController::class, 'getOptionVariant'])->name('orders.getOptionVariant');
    Route::resource('orders', OrderController::class);
});



Route::resource('baskets', BasketController::class);
