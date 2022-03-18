<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateArticleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_create_articles()
    {
        $article = Article::factory()->create();
        $response = $this->patchjson(route('api.v1.articles.update', $article), [
            'title' => 'Updated artículo',
            'slug' => 'updated-articulo',
            'content' => 'Article updated content',
        ])->assertOk();

        $response->assertHeader(
            'Location',
            route('api.v1.articles.show', $article)
        );
        $response->assertExactJson([
            'data' => [
                'type' => 'articles',
                'id' => (string) $article->getRouteKey(),
                'attributes' => [
                    'title' => 'Updated artículo',
                    'slug' => 'updated-articulo',
                    'content' => 'Article updated content',
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
        $article = Article::factory()->create();
        $this->patchJson(route('api.v1.articles.update', $article), [
            'slug' => 'updated-articulo',
            'content' => 'Contenido updated del artículo',
        ])->assertJsonApiValidationErrors('title');

    }

    /** @test */
    public function title_must_be_at_least_4_characters()
    {
        $article = Article::factory()->create();
        $this->patchJson(route('api.v1.articles.update', $article), [
            'title' => 'Nue',
            'slug' => 'nuevo-articulo',
            'content' => 'Contenido del artículo',
        ])->assertJsonApiValidationErrors('title');

    }

    /** @test */
    public function slug_is_required()
    {
        $article = Article::factory()->create();
        $this->patchJson(route('api.v1.articles.update', $article), [
            'title' => 'Nuevo articulo',
            'content' => 'Contenido del artículo',
        ])->assertJsonApiValidationErrors('slug');

    }

    /** @test */
    public function content_is_required()
    {
        $article = Article::factory()->create();
        $this->patchJson(route('api.v1.articles.update', $article), [
            'title' => 'Nuevo articulo',
            'slug' => 'nuevo-articulo',
        ])->assertJsonApiValidationErrors('content');

    }
}
