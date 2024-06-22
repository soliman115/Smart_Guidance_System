<?php

namespace App\Http\Controllers\ApiControllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterFormRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(RegisterFormRequest $request){
        // Validate and get validated data
        $validatedData = $request->validated();
        // Hash the password
        $validatedData['password'] = Hash::make( $validatedData['password']);
        // Set default values
        $validatedData['image'] = 'images/users/default.png';
        // Create the user
        $user = User::create( $validatedData);
        // Generate authentication token
        $token = Auth::login($user);
        // Return response
        return response()->json([
            'message' => 'User created successfully',
            'user' => $user,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ],201);

    }
}
