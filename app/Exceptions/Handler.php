<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(fn (NotFoundHttpException $e) => throw new JsonApi\NotFoundHttpException);

        $this->renderable(fn (BadRequestHttpException $e) => throw new JsonApi\BadRequestHttpException($e->getMessage()));

        $this->renderable(fn (AuthenticationException $e) => throw new JsonApi\AuthenticationException);
    }

    protected function invalidJson($request, ValidationException $exception): JsonResponse
    {
        if (! $request->routeIs('api.v1.login')) {
            $title = $exception->getMessage();
            /* en vez de foreach, se puede utilizar collections */
            return response()->json([
                'errors' => collect($exception->errors())
                    ->map(function ($messages, $field) use ($title) {
                        return [
                            'title'  => $title,
                            'detail' => $messages[0],
                            'source' => [
                                'pointer' => "/".str_replace('.', '/', $field)
                            ]
                        ];
                    })->values()
            ], 422, [
                'content-type' => 'application/vnd.api+json'
            ]);
        }
        return parent::invalidJson($request, $exception);
    }
}
