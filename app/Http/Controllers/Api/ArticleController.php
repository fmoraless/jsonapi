<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveArticleRequest;
use App\Http\Resources\ArticleCollection;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class ArticleController extends Controller
{
    public function __construct() {
        $this->middleware('auth:sanctum',[
           'only' => ['store', 'update', 'destroy']
        ]);
    }
    public function show($article): JsonResource
    {
        $article = Article::where('slug', $article)
            ->allowedIncludes(['category', 'author'])
            ->sparseFieldset()
            ->firstOrFail();

        return ArticleResource::make($article);
    }

    public function index(): AnonymousResourceCollection
    {

        $articles = Article::query()
            ->allowedIncludes(['category', 'author'])
            ->allowedFilters(['title', 'content', 'month', 'year', 'categories'])
            ->allowedSorts(['title', 'content'])
            ->sparseFieldset()
            ->jsonPaginate();

        return ArticleResource::collection($articles);
    }

    public function store(SaveArticleRequest $request): ArticleResource
    {
        $this->authorize('create', new Article);

        $article = Article::create($request->validated());

        return ArticleResource::make($article);
    }

    public function update(Article $article, SaveArticleRequest $request): ArticleResource
    {
        $this->authorize('update', $article);

        $article->update($request->validated());

        return ArticleResource::make($article);
    }

    public function destroy(Article $article, Request $request): Response
    {
        $this->authorize('delete', $article);

        $article->delete();
        return response()->noContent();
    }
}
