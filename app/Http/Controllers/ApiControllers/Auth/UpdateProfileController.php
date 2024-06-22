<?php

namespace App\Http\Controllers\ApiControllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateInfoFormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UpdateProfileController extends Controller
{
    public function update_info(UpdateInfoFormRequest $request)
    {
        // Get the authenticated user
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized access'], 401);
        }

        // Get validated data
        $data = $request->validated();

        // Hash the password if it is present in the request data
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $name = time() . rand(0, 9999999999999) . '_image.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/users'), $name);
            $data['image'] = 'images/users/' . $name;
        }

        // Update user information
        try {
            $user->update($data);
            return response()->json([
                'message' => 'User updated successfully',
                'status' => 200,
                'user' => $user
            ]);
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            Log::error('Failed to update user information', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Failed to update user information. Please try again.',
                'status' => 500
            ], 500);
        }
    }
}
