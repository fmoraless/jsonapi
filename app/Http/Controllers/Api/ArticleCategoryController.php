<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleCategoryController extends Controller
{
    public function index(Article $article)
    {
        return CategoryResource::indetifier($article->category);
    }

    public function show(Article $article)
    {
        return CategoryResource::make($article->category);
    }
}
