<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\CategoryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::apiResource('articles',ArticleController::class);
Route::apiResource('categories',CategoryController::class)
    ->only('index', 'show');
Route::apiResource('authors',AuthorController::class)
    ->only('index', 'show');

Route::get('articles/{article}/relationships/category', fn() => 'TODO')
    ->name('articles.relationships.category');

Route::get('articles/{article}/category', fn() => 'TODO')
    ->name('articles.category');
