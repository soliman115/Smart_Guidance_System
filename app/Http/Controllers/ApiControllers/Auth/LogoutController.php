<?php

namespace App\Http\Controllers\ApiControllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'message' => 'User logged out successfully',
        ]);
    }
}
