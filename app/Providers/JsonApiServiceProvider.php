<?php

namespace App\Providers;

use App\JsonApi\JsonApiQueryBuilder;
use App\JsonApi\JsonApiTestResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Assert as PHPUnit;
use PHPUnit\Framework\ExpectationFailedException;

class JsonApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Builder::mixin(new JsonApiQueryBuilder());

        TestResponse::mixin(new JsonApiTestResponse());
    }
}
