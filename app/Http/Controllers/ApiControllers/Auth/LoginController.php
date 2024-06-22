<?php

namespace App\Http\Controllers\ApiControllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginFormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(LoginFormRequest $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            ];
        $token =Auth::attempt($credentials);
        if (!$token) {
        return response()->json([
            'status' => 'error',
            'message' => 'email or password is not correct',
        ], 401);
                    }
            $user = Auth::user();
            return response()->json([
                'status' => 'success',
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ],200);
    }
}

