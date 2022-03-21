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
    public function can_update_articles()
    {
        $article = Article::factory()->create();
        $response = $this->patchjson(route('api.v1.articles.update', $article), [
            'title' => 'Updated artículo',
            'slug' => $article->slug,
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
                    'slug' => $article->slug,
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
    public function slug_must_be_unique()
    {
        /* un articulo para editar */
        $article1 = Article::factory()->create();
        /* un articulo para reutilizar */
        $article2 = Article::factory()->create();

        $this->patchJson(route('api.v1.articles.update', $article1), [
            'title' => 'Nuevo articulo',
            'slug' => $article2->slug,
            'content' => 'Contenido del artículo',
        ])->assertJsonApiValidationErrors('slug');

    }

    /** @test */
    public function slug_must_only_contain_letters_numbers_and_dashes()
    {
        $article = Article::factory()->create();

        $this->patchJson(route('api.v1.articles.update', $article), [
            'title' => 'Nuevo articulo',
            'slug' => '$%^&',
            'content' => 'Contenido del artículo',
        ])->assertJsonApiValidationErrors('slug');

    }

    /** @test */
    public function slug_must_not_contain_dashes()
    {
        $article = Article::factory()->create();

        $this->patchJson(route('api.v1.articles.update', $article), [
            'title' => 'Nuevo articulo',
            'slug' => 'with_underscores',
            'content' => 'Contenido del artículo',
        ])->assertSee(trans('validation.no_underscores', [
            'attribute' => 'data.attributes.slug'
        ]))->assertJsonApiValidationErrors('slug');

    }

    /** @test */
    public function slug_must_not_start_with_dashes()
    {
        $article = Article::factory()->create();

        $this->patchJson(route('api.v1.articles.update', $article), [
            'title' => 'Nuevo articulo',
            'slug' => '-starts-with-dashes',
            'content' => 'Contenido del artículo',
        ])->assertSee(trans('validation.no_starting_dashes', [
            'attribute' => 'data.attributes.slug'
        ]))->assertJsonApiValidationErrors('slug');

    }

    /** @test */
    public function slug_must_not_end_with_dashes()
    {
        $article = Article::factory()->create();

        $this->patchJson(route('api.v1.articles.update', $article), [
            'title' => 'Nuevo articulo',
            'slug' => 'ends-with-dashes-',
            'content' => 'Contenido del artículo',
        ])->assertSee(trans('validation.no_ending_dashes', [
            'attribute' => 'data.attributes.slug'
        ]))->assertJsonApiValidationErrors('slug');

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
