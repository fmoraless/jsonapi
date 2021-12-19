<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    protected function invalidJson($request, ValidationException $exception): JsonResponse
    {
        $title = $exception->getMessage();
        /*foreach ($exception->errors() as $field => $messages) {
            $pointer = "/".str_replace('.', '/', $field);

            $errors[] = [
                'title'  => $title,
                'detail' => $messages[0],
                'source' => [
                    'pointer' => $pointer
                ]
            ];
        }*/
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
        ], 422);
    }
}
