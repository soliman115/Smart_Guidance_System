<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Mail\MyMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    //
    public function send()
    {
        $email = request('email');
        $user = User::query()->where('email','=',$email)->first();
        if($user != null){
            Mail::to($email)->send(new MyMail(
                ['title'=>'forget password','body'=>'your code is '.$user->verfication]
            ));
        }else{
            return response()->json(['error'=>'user not found with this  email'],400);
        }

    }
}
