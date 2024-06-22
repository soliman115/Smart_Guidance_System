<?php

namespace App\Http\Controllers\ApiControllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ForgetPasswordRequest;
use App\Models\User;
use App\Notifications\ResetPasswordVerificationNotification;
use Illuminate\Http\Request;

class ForgetPasswordController extends Controller
{
    public function ForgetPassword( ForgetPasswordRequest $request){

        $input =$request->only('email');
        $user= User::where('email',$input)->first();

        $user->notify(new ResetPasswordVerificationNotification());

        return response()->json([
            'success' => true,
            'email' => $user->email,
            'message' => 'Verification code sent successfully',
        ], 200);
    }
}
