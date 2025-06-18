<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request){

        $credentials = $request->only('email', 'password');

        if(Auth::attempt($credentials)){

            $user = $request->user();

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'type_token' => 'Bearer',
                'access_token' => $token
            ]);
        }

        return response()->json([
            'message' => 'Inválid user'
        ]);
    }
}