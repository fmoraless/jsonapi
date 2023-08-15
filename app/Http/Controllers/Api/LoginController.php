<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'device_name' => ['required']
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            // invalid credentials
            throw ValidationException::withMessages([
                'email' => [__('auth.failed')],
            ]);
        }

        $plainTextToken = $user->createToken(
            $request->device_name,
            []
        )->plainTextToken;

        return response()->json([
           'plain-text-token' => $plainTextToken
        ]);
    }
}
