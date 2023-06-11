<?php

use App\Http\Controllers\Api\ArticleAuthorController;
use App\Http\Controllers\Api\ArticleCategoryController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Middleware\ValidateJsonApiDocument;
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
/* category */
Route::get('articles/{article}/relationships/category', [
        ArticleCategoryController::class, 'index'
    ])->name('articles.relationships.category');

Route::get('articles/{article}/category', [
        ArticleCategoryController::class, 'show'
    ])->name('articles.category');

Route::patch('articles/{article}/relationships/category', [
    ArticleCategoryController::class, 'update'
])->name('articles.relationships.category');

/* author */
Route::get('articles/{article}/relationships/author', [
        ArticleAuthorController::class, 'index'
    ])->name('articles.relationships.author');

Route::get('articles/{article}/author', [
        ArticleAuthorController::class, 'show'
    ])->name('articles.author');

Route::patch('articles/{article}/relationships/author', [
    ArticleAuthorController::class, 'update'
])->name('articles.relationships.author');

Route::withoutMiddleware(ValidateJsonApiDocument::class)
    ->post('login', LoginController::class)->name('login');
