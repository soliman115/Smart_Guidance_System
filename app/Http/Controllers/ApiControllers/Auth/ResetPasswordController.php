<?php

namespace App\Http\Controllers\ApiControllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Models\User;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    private $otp;

    public function __construct(Otp $otp)
    {
        $this->otp = new Otp;
    }
    public function verifyOtp(VerifyOtpRequest $request)
    {
        // Validate OTP
        $otpValidation = $this->otp->validate($request->email, $request->otp);

        if (! $otpValidation->status) {
            return response()->json(['error'=>$otpValidation], 401);
        }

        // OTP is valid, generate a token
        $token = Password::createToken(User::where('email', $request->email)->first());

        // Return the token
        return response()->json([
            'success' => true,
            'message' => 'OTP verified successfully',
            'token' => $token,
        ], 200);
    }
    public function updatePassword(ResetPasswordRequest $request)
    {
        $resetPasswordStatus = Password::reset(
            $request->only('email','token','password', 'password_confirmation'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );
        if ($resetPasswordStatus == Password::PASSWORD_RESET) {
            return response()->json([
                'success' => true,
                'message' => 'User password updated successfully',
            ], 200);
        } else {
            return response()->json(['error' => 'Invalid or expired token'], 401);
        }
    }
}
