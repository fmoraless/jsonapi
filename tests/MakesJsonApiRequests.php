<?php

namespace Tests;

use App\JsonApi\Document;
use Closure;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
use PhpParser\Node\Scalar\String_;
use PHPUnit\Framework\Assert as PHPUnit;
use PHPUnit\Framework\ExpectationFailedException;

trait MakesJsonApiRequests
{
    protected bool $formatJsonApiDocument = true;

    /**
     * @param $uri
     * @param array $data
     * @return array
     */
    public function getFormattedData($uri, array $data): array
    {
        $path = parse_url($uri)['path'];
        $type = $type = (string) Str::of($path)->after('api/v1/')->before('/');
        $id = (string) Str::of($path)->after($type)->replace('/', '');

        //dump(array_filter($formattedData['data']));
        return Document::type($type)
            ->id($id)
            ->attributes($data)
            ->relationshipData($data['_relationships'] ?? [])
            ->toArray();

    }

    protected function setUp(): void
    {
        parent::setUp();

    }

    public function withoutJsonApiDocumentFormatting()
    {
        $this->formatJsonApiDocument = false;
    }
    public function json($method, $uri, array $data = [], array $headers = []): TestResponse
    {
        $headers['accept'] = 'application/vnd.api+json';

        if ($this->formatJsonApiDocument){
            $formattedData = $this->getFormattedData($uri, $data);
            //dump($formattedData);
        }


        return parent::json($method, $uri, $formattedData ?? $data, $headers);
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

}
