<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Auth Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for authentication. 
    | 
    |
    */

    /**
     * Authenticate the user by email and password.
     * "access_token" will be provided after successful authentication.
     * it also returns "error" property for better error handling on the client side
     * 
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json([
                'msg' => 'Unauthorized',
                'error' =>  true
            ], 401);
        }

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60, // the token has one hour ttl
            'error'        => false
        ]);
    }

}