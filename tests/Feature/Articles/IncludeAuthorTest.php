<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IncludeAuthorTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function can_include_related_author_of_an_article()
    {
        $article = Article::factory()->create();

        //acceder a ruta
        /* articles/the-slug?include=author */
        $url = route('api.v1.articles.show', [
            'article' => $article,
            'include' => 'author'
        ]);

        $this->getJson($url)->assertJson([
            'included' => [
                [
                    'type' => 'authors',
                    'id' => $article->author->getRouteKey(),
                    'attributes' => [
                        'name' => $article->author->name
                    ]
                ]
            ]
        ]);
    }

    /** @test */
    public function can_include_related_authors_of_multiple_articles(){

        $article1 = Article::factory()->create()->load('author');
        $article2 = Article::factory()->create()->load('author');

        $url = route('api.v1.articles.index', [
            'include' => 'author'
        ]);
        /* escuchar consultas SQL */
        \DB::listen(function($query){
            var_dump($query->sql);
        });
        $this->getJson($url)->assertJson([
            'included' => [
                [
                    'type' => 'authors',
                    'id' => $article1->author->getRouteKey(),
                    'attributes' => [
                        'name' => $article1->author->name
                    ]
                ],
                [
                    'type' => 'authors',
                    'id' => $article2->author->getRouteKey(),
                    'attributes' => [
                        'name' => $article2->author->name
                    ]
                ]
            ]
        ]);
    }
}
