<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


Route::prefix('products')->controller(ProductController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('{product}', 'show');
    Route::post('/', 'store');
    Route::put('{product}', 'update');
    Route::delete('{product}', 'destroy');
});

