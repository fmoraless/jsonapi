<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IncludeCategoryTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function can_include_related_category_of_an_article()
    {
        $article = Article::factory()->create();

        //construir ruta
        $url = route('api.v1.articles.show', [
            'article' => $article,
            'include' => 'category'
        ]);

        //dd(urldecode($url));
        //http://jsonapi.dev.com/api/v1/articles/molestiae-deserunt-non-et-repellendus-ipsa-dolore-maxime?include=category"

        $this->getJson($url)->assertJson([
               'included' => [
                   [
                       'type' => 'categories',
                        'id' => $article->category->getRouteKey(),
                        'attributes' => [
                           'name' => $article->category->name
                        ]
                   ]
               ]
            ]);
    }

    /** @test */
    public function can_include_related_categories_of_multiple_articles(){
        //Article::factory()->count(13)->create();
        $article1 = Article::factory()->create()->load('category');
        $article2 = Article::factory()->create()->load('category');

        $url = route('api.v1.articles.index', [
            'include' => 'category'
        ]);
        /* escuchar consultas SQL */
        \DB::listen(function($query){
            var_dump($query->sql);
        });
        $this->getJson($url)->assertJson([
            'included' => [
                [
                    'type' => 'categories',
                    'id' => $article1->category->getRouteKey(),
                    'attributes' => [
                        'name' => $article1->category->name
                    ]
                ],
                [
                    'type' => 'categories',
                    'id' => $article2->category->getRouteKey(),
                    'attributes' => [
                        'name' => $article2->category->name
                    ]
                ]
            ]
        ]);
    }

    /** @test */
    public function cannot_include_unknown_relationships()
    {
        $article = Article::factory()->create();

        //construir ruta
        /* articles/the-slug?include=unknown*/
        $url = route('api.v1.articles.show', [
            'article' => $article,
            'include' => 'unknown,unknown2'
        ]);

        $this->getJson($url)->assertStatus(400);

        //construir ruta
        /* articles?include=unknown*/
        $url = route('api.v1.articles.index', [
            'include' => 'unknown,unknown2'
        ]);
        $this->getJson($url)->assertJsonApiError(
            title: "Bad Request",
            detail: "The included relationship 'unknown' is not allowed in the 'articles' resource.",
            status: "400"
        );
    }
}
