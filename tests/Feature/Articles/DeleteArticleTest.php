<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeleteArticleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_delete_article()
    {
        $article = Article::factory()->create();

        $this->deleteJson(route('api.v1.articles.destroy', $article))
            ->assertJsonApiError(
                title: 'Unauthenticated',
                detail: 'This action requires authentication.',
                status: '401'
            );

    }

    /** @test */
    public function can_delete_owned_article()
    {
        $article = Article::factory()->create();
        Sanctum::actingAs($article->author);

        $this->deleteJson(route('api.v1.articles.destroy', $article))
            ->assertNocontent();

        $this->assertDatabaseCount('articles', 0);
    }

    /** @test */
    public function can_delete_article_owned_by_other_users()
    {
        $article = Article::factory()->create();
        Sanctum::actingAs(User::factory()->create());

        $this->deleteJson(route('api.v1.articles.destroy', $article))
            ->assertForbidden();

        $this->assertDatabaseCount('articles', 1);
    }
}
