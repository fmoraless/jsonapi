<?php

namespace Tests;

use Illuminate\Testing\TestResponse;

trait MakesJsonApiRequest
{
    protected function setUp(): void
    {
        parent::setUp();

        /* registrar un macro*/
        TestResponse::macro('assertJsonApiValidationErrors',
            $this->assertJsonApiValidationErrors());
    }
    public function json($method, $uri, array $data = [], array $headers = []): TestResponse
    {
        $headers['accept'] = 'application/vnd.api+json';
        return parent::json($method, $uri, $data, $headers);
    }
    public function postJson($uri, array $data = [], array $headers = []): TestResponse
    {
        $headers['content-type'] = 'application/vnd.api+json';
        return parent::postJson($uri, $data, $headers);
    }
    public function patchJson($uri, array $data = [], array $headers = []): TestResponse
    {
        $headers['content-type'] = 'application/vnd.api+json';
        return parent::patchJson($uri, $data, $headers);
    }

    /**
     * @return \Closure
     */
    protected function assertJsonApiValidationErrors(): \Closure
    {
        return function ($attribute) {
            /** @var TestResponse $this */

            try {
                $this->assertJsonFragment([
                    'source' => ['pointer' => '/data/attributes/' . $attribute]
                ]);
            }catch (\Exception $e) {
                dd($e);
            }

            $this->assertJsonStructure([
                'errors' => [
                    ['title', 'detail', 'source' => ['pointer']]
                ]
            ]);
            $this->assertHeader(
                'content-type', 'application/vnd.api+json'
            )->assertStatus(422);
        };
    }
}
