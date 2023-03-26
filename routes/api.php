<?php

use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// blog categories
Route::apiResource('blog-categories', \App\Http\Controllers\BlogCategoryController::class);

// blog
Route::name('blogs.')->group(function () {
    Route::get('blogs', [\App\Http\Controllers\BlogController::class, 'index'])->name('index');
    Route::get('blogs/{slug}', [\App\Http\Controllers\BlogController::class, 'show'])->name('show');
    Route::post('blogs', [\App\Http\Controllers\BlogController::class, 'storeOrUpdate'])->name('storeOrUpdate');
    Route::delete('blogs/{blog}', [\App\Http\Controllers\BlogController::class, 'destroy'])->name('destroy');
    Route::get('blogs/category/{blogCategory}',  [\App\Http\Controllers\BlogController::class, 'showByCategory'])->name('blogsByCategory');
});
