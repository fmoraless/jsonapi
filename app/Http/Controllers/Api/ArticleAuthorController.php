<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthorResource;
use App\Http\Resources\CategoryResource;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleAuthorController extends Controller
{
    public function index(Article $article)
    {
        return AuthorResource::indetifier($article->author);
    }

    public function show(Article $article)
    {
        return AuthorResource::make($article->author);
    }

    public function update(Article $article, Request $request)
    {
        $userId = $request->input('data.id');

        $article->update(['user_id' => $userId]);

        return AuthorResource::indetifier($article->author);
    }
}
