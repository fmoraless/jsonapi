<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PhpParser\Node\Scalar\String_;
use Tests\TestCase;

class ListArticlesTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function can_fetch_a_single_article()
    {
        $this->withoutExceptionHandling();

        $article = Article::factory()->create();

        /*$response = $this->getJson('/api/v1/articles/'.$article->getRouteKey());*/
        $response = $this->getJson(route('api.v1.articles.show', $article));

        $response->assertJsonApiResource($article, [
            'title' => $article->title,
            'slug' => $article->slug,
            'content' => $article->content
        ])->assertJsonApiRelationshipsLinks($article, ['category', 'author']);
    }

    /** @test */
    public function can_fetch_all_articles()
    {
        /*$this->withoutExceptionHandling();*/
        $articles = Article::factory()->count(3)->create();
        /* escuchar consultas SQL */
        \DB::listen(function($query){
            var_dump($query->sql);
        });
        $response = $this->getJson(route('api.v1.articles.index'));

        $response->assertJsonApiResourceCollection($articles, [
            'title', 'slug', 'content'
        ]);
    }

    /** @test */
    public function it_returns_a_json_api_error_object_when_an_article_is_not_found()
    {
        //$this->withoutExceptionHandling();
        $response = $this->getJson(route('api.v1.articles.show', 'not-existing'));

        $response->assertJsonApiError(
            title: 'Not Found',
            detail: "No records found with the id 'not-existing' in the 'articles' resource.",
            status: "404"
        );
    }

}
