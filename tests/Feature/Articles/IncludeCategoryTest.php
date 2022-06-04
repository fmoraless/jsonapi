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
    public function can_include_relates_category_of_an_article()
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
}
