<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthorRelationshipTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function can_fetch_the_associated_author_identifier()
    {
        $article = Article::factory()->create();
        $url = route('api.v1.articles.relationships.author', $article);

        $response = $this->getJson($url);

        $response->assertExactJson([
            'data' => [
                'id' => $article->author->getRouteKey(),
                'type' => 'authors',
            ],
        ]);
    }

    /** @test */
    public function can_fetch_the_associated_author_resource()
    {
        $article = Article::factory()->create();
        $url = route('api.v1.articles.author', $article);

        $response = $this->getJson($url);

        $response->assertJson([
            'data' => [
                'id' => $article->author->getRouteKey(),
                'type' => 'authors',
                'attributes' => [
                    'name' => $article->author->name,
                ],
            ],
        ]);
    }

    /** @test */
    public function can_update_the_associated_author()
    {
        $author = User::factory()->create();
        $article = Article::factory()->create();

        $url = route('api.v1.articles.relationships.author', $article);

        $this->withoutJsonApiDocumentFormatting();

        $response = $this->patchJson($url, [
            'data' => [
                'type' => 'authors',
                'id' => $author->getRouteKey(),
            ]
        ]);

        $response->assertExactJson([
            'data' => [
                'type' => 'authors',
                'id' => $author->getRouteKey()
            ],
        ]);

        $this->assertDatabaseHas('articles',[
            'title' => $article->title,
            'user_id' => $author->id,
        ]);

    }
}
