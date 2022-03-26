<?php

namespace App\Providers;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

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
        Builder::macro('allowedSorts', function ($allowedSorts) {

            if (request()->filled('sort')) {
                $sortFields = explode(',', request()->input('sort'));

                $allowedSorts = ['title', 'content'];

                foreach ($sortFields as $sortField) {
                    $sortDirection = Str::of($sortField)->startsWith('-') ? 'desc' : 'asc';

                    $sortField = ltrim($sortField, '-');

                    abort_unless(in_array($sortField, $allowedSorts), 400);

                    $this->orderBy($sortField, $sortDirection);
                }
            }

            return $this;
        });
    }
}
