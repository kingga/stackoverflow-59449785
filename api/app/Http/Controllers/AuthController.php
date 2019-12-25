<?php

namespace App\Http\Controllers;

use App\User;
use Lcobucci\JWT\Parser;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        if (Auth::guard('web')->attempt($request->only('email', 'password'))) {
            $user = User::where('email', $request->email)->first();

            // // Check if this user has verified their email and if they haven't tell them to do it.
            // if (!$user->email_verified_at) {
            //     return response(['error' => 'Verify you email address']);
            // }

            $token = $user->createToken('Isaac Skelton Password Grant Client')->accessToken;

            return response(['token' => $token, 'user' => $user], 200);
        } else {
            return response(['error' => 'Invalid credentials'], 422);
        }
    }

    public function logout(Request $request)
    {
        $value = $request->bearerToken();
        $id = (new Parser())->parse($value)->getClaim('jti');
        $token = $request->user()->tokens->find($id);
        $token->revoke();

        return response([
            'message' => 'You have been successfully logged out!',
        ], 200);
    }
}
