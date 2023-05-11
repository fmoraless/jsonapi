<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
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
        $this->renderable(function (NotFoundHttpException $e, $request) {
            $id = $request->input('data.id');
            $type = $request->input('data.type');

            return response()->json([
                'errors' => [
                    'title' => 'Not Found',
                    'detail' => "No records found with the id '{$id}' in the '{$type}' resource.",
                    'status' => '404'
                ]
            ], 404);
        });
    }

    protected function invalidJson($request, ValidationException $exception): JsonResponse
    {
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
}
