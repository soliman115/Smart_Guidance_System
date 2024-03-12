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
        $data['verfication'] = rand(0,99999);
        // $data['password'] = 123
        User::query()->create($data);
        return response()->json(['message'=>'user registered successfully']);
    }

    public function check_code(){
        $user = User::query()->where('verfication','=',request('code'))->first();
        if($user == null){
            return response()->json(['error'=>'there is no user with this data'],400);
        }else{
            return response()->json(['data'=>$user]);
        }
    }

    public function new_password(){
        $user = User::query()->where('id','=',request('id'))->first();
        if(request('password') == request('password_confirmation')){
            $user->update(['password'=>bcrypt(request('password'))]);
            return response()->json(['data'=>$user,'message'=>'user password updated successfully']);
        }else{
            return response()->json(['error'=>'password and confirmation is not correct'],400);
        }
    }

    public function send_email(){
        $user = User::query()->where('email','=',request('email'))->first();
        if($user == null){
            return response()->json(['error'=>'there is no user with this email'],400);
        }
        $details = [
            'title' => 'Mail from Smart guidance system',
            'body' => 'This is your code to set up new password '.$user->verfication
        ];
    
        \Mail::to($user->email)->send(new \App\Mail\MyMail($details));
    
        
    }
}
