<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginFormRequest;
use App\Http\Requests\RegisterFormRequest;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    //
    public function login(LoginFormRequest $request)
    {
        $check =  $request->validated();
        if(auth()->attempt($check)){
            $user = auth()->user();
            return response()->json($user);
        }else{
            return response()->json(['error'=>'email or password is not correct'],400);
        }

    }
    public function register(RegisterFormRequest $request)
    {
        $data =  $request->validated();
        $data['password'] = bcrypt($data['password']);
        $data['image'] = 'images/users/default.png';
       ;
        // $data['password'] = 123
        User::query()->create($data);
        return response()->json(['message'=>'user registered successfully']);
    }
}
