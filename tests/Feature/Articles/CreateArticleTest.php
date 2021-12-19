<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateArticleTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function can_create_articles()
    {
        $this->withoutExceptionHandling();
        $response = $this->postJson(route('api.v1.articles.store'), [
           'data' => [
               'type' => 'articles',
               'attributes' => [
                   'title' => 'Nuevo artículo',
                   'slug' => 'nuevo-articulo',
                   'content' => 'Contenido del artículo',
               ]
           ]
        ]);
        $response->assertCreated();
        $article = Article::first();
        $response->assertHeader(
            'Location',
            route('api.v1.articles.show', $article)
        );
        $response->assertExactJson([
           'data' => [
               'type' => 'articles',
               'id' => (string) $article->getRouteKey(),
               'attributes' => [
                   'title' => 'Nuevo artículo',
                   'slug' => 'nuevo-articulo',
                   'content' => 'Contenido del artículo',
               ],
               'links' => [
                   'self' => route('api.v1.articles.show', $article)
               ]
           ]
        ]);
    }

    /** @test */
    public function title_is_required()
    {
        $response = $this->postJson(route('api.v1.articles.store'), [
            'data' => [
                'type' => 'articles',
                'attributes' => [
                    'slug' => 'nuevo-articulo',
                    'content' => 'Contenido del artículo',
                ]
            ]
        ]); //se puede usar ->dump() para ver detalle de error
        /*se espera un error de validacion en el campo title*/
        $response->assertJsonValidationErrors('data.attributes.title');
        /*$article = Article::first();*/
        /*
             * $response->assertHeader(
                'Location',
                route('api.v1.articles.show', $article)
            );
        */
        /*
             * $response->assertExactJson([
                'data' => [
                    'type' => 'articles',
                    'id' => (string) $article->getRouteKey(),
                    'attributes' => [
                        'title' => 'Nuevo artículo',
                        'slug' => 'nuevo-articulo',
                        'content' => 'Contenido del artículo',
                    ],
                    'links' => [
                        'self' => route('api.v1.articles.show', $article)
                    ]
                ]
            ]);
        */
    }

    /** @test */
    public function title_must_be_at_least_4_characters()
    {
        $response = $this->postJson(route('api.v1.articles.store'), [
            'data' => [
                'type' => 'articles',
                'attributes' => [
                    'title' => 'Nue',
                    'slug' => 'nuevo-articulo',
                    'content' => 'Contenido del artículo',
                ]
            ]
        ]); //se puede usar ->dump() para ver detalle de error
        /*se espera un error de validacion en el campo title*/
        $response->assertJsonValidationErrors('data.attributes.title');

    }

    /** @test */
    public function slug_is_required()
    {
        $response = $this->postJson(route('api.v1.articles.store'), [
            'data' => [
                'type' => 'articles',
                'attributes' => [
                    'title' => 'Nuevo articulo',
                    'content' => 'Contenido del artículo',
                ]
            ]
        ]); //se puede usar ->dump() para ver detalle de error
        /*se espera un error de validacion en el campo title*/
        $response->assertJsonValidationErrors('data.attributes.slug');

    }

    /** @test */
    public function content_is_required()
    {
        $response = $this->postJson(route('api.v1.articles.store'), [
            'data' => [
                'type' => 'articles',
                'attributes' => [
                    'title' => 'Nuevo articulo',
                    'slug' => 'nuevo-articulo',
                ]
            ]
        ]); //se puede usar ->dump() para ver detalle de error
        /*se espera un error de validacion en el campo title*/
        $response->assertJsonValidationErrors('data.attributes.content');

    }
}
