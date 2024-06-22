<?php

namespace App\Http\Controllers\ApiControllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function profile()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized access'], 401);
        }

        return response()->json($user);
    }
}

