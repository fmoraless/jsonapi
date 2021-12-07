<?php

namespace Tests\Feature;

use App\Http\Middleware\ValidateJsonApiHeaders;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ValidateJsonApiHeaderTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function accept_header_must_be_present_in_all_requests()
    {
        Route::get('test_route', function () {
            return 'OK';
        })->middleware(ValidateJsonApiHeaders::class);

        $this->get('test_route')->assertStatus(406);

        $this->get('test_route', [
            'accept' => 'application/vnd.api+json'
        ])->assertSuccessful();
    }

    /** @test */
    public function content_type_header_must_be_present_in_all_post_requests()
    {
        Route::post('test_route', function () {
            return 'OK';
        })->middleware(ValidateJsonApiHeaders::class);

        $this->post('test_route', [], [
            'accept' => 'application/vnd.api+json'
        ])->assertStatus(415);

        /* verrifica respuesta de cuando si se envian los headers correctos. */
        $this->post('test_route', [], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json'
        ])->assertSuccessful();
    }

    /** @test */
    public function content_type_header_must_be_present_in_all_patch_requests()
    {
        Route::post('test_route', function () {
            return 'OK';
        })->middleware(ValidateJsonApiHeaders::class);

        $this->post('test_route', [], [
            'accept' => 'application/vnd.api+json'
        ])->assertStatus(415);

        /* verrifica respuesta de cuando si se envian los headers correctos. */
        $this->post('test_route', [], [
            'accept' => 'application/vnd.api+json',
            'content-type' => 'application/vnd.api+json'
        ])->assertSuccessful();
    }
}
