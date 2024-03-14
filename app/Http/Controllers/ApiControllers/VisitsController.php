<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VisitsController extends Controller
{
    public function storeVisit(Request $request)
    {
        //Get authenticated user ID
        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['error' => 'Unauthorized access'], 401);
        }

        // Validate the request
        $request->validate([
            'place' => 'required|exists:places,id',
        ]);

        // Get the selected place ID from the request
        $selectedPlaceId = $request->input('place');

        // Record the visit
        try {
            $visit = Visit::create([
                'user_id' => $userId,
                'place_id' => $selectedPlaceId,
                'visited_at' => Carbon::now(),
            ]);
            return response()->json([
                'message' => 'Visit created successfully',
                'visit' => $visit,
            ], 201);
            } catch (\Illuminate\Database\QueryException $e) {
                return response()->json(['error' => 'Database error. Please try again.'], 500);
            } catch (\Illuminate\Validation\ValidationException $e) {
                return response()->json(['error' => $e->validator->errors()->first()], 422);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Failed to create visit. Please try again.'], 500);
            }
    }
}
