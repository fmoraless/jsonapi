<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\Request;

class ValidateJsonApiHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     *
     * @throws HttpException
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->header('accept') !== 'application/vnd.api+json') {
            throw new HttpException(406);
        }

        if ($request->isMethod('POST')) {
            if ($request->header('content-type') !== 'application/vnd.api+json') {
                throw new HttpException(415);
            }
        }
    }
}
