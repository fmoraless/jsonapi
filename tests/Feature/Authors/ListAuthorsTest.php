<?php

namespace Tests\Feature\Authors;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use PharIo\Manifest\Author;
use Tests\TestCase;

class ListAuthorsTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function can_fetch_a_single_author()
    {
        $author = User::factory()->create();

        $response = $this->getJson(route('api.v1.authors.show', $author));

        $response->assertJsonApiResource($author, [
            'name' => $author->name
        ]);

        $this->assertTrue(
            Str::isUuid($response->json('data.id')),
            "The author's ID is not a UUID."
        );
    }

    /** @test */
    public function can_fetch_all_authors()
    {
        /*$this->withoutExceptionHandling();*/
        $authors = User::factory()->count(3)->create();

        $response = $this->getJson(route('api.v1.authors.index'));

        $response->assertJsonApiResourceCollection($authors, [
            'name'
        ]);
    }
}
