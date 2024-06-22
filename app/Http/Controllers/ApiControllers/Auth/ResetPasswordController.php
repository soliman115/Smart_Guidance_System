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
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    private $otp;

    public function __construct()
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
        $token = Str::random(60);

        // Store the token in cache with the email
        Cache::put($token, $request->email, now()->addMinutes(30));

        // Return the token
        return response()->json([
            'success' => true,
            'message' => 'OTP verified successfully',
            'token' => $token,
        ], 200);
    }
    public function updatePassword(ResetPasswordRequest $request)
    {
        // Retrieve email from token
        $email = Cache::get($request->token);

        if (!$email) {
            return response()->json(['error' => 'Invalid or expired token'], 401);
        }

        // Update the user's password
        $user = User::where('email', $email)->firstOrFail();
        $user->update(['password' => Hash::make($request->password)]);
        $user->tokens()->delete();

        // Invalidate the token
        Cache::forget($request->token);

        return response()->json([
            'success' => true,
            'message' => 'User password updated successfully',
        ], 200);
    }
}
