<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryRelationshipTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function can_fetch_the_associated_category_identifier()
    {
        $article = Article::factory()->create();
        $url = route('api.v1.articles.relationships.category', $article);

        $response = $this->getJson($url);

        $response->assertExactJson([
            'data' => [
                'id' => $article->category->getRouteKey(),
                'type' => 'categories',
            ],
        ]);
    }

    /** @test */
    public function can_fetch_the_associated_category_resource()
    {
        $article = Article::factory()->create();
        $url = route('api.v1.articles.category', $article);

        $response = $this->getJson($url);

        $response->assertJson([
            'data' => [
                'id' => $article->category->getRouteKey(),
                'type' => 'categories',
                'attributes' => [
                    'name' => $article->category->name,
                ],
            ],
        ]);
    }
}
