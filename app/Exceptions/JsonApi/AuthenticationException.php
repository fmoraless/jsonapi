<?php

namespace App\Exceptions\JsonApi;

use Exception;

class AuthenticationException extends Exception
{
    /**
     * Render the exception as an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response()->json([
            'errors' => [
                [
                    'title' => 'Unauthenticated',
                    'detail' => 'This action requires authentication.',
                    'status' => '401'
                ]
            ]
        ], 401);
    }
}
