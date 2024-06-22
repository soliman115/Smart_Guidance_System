<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVisitRequest;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VisitsController extends Controller
{
    public function storeVisit(StoreVisitRequest  $request): JsonResponse
    {
        //Get authenticated user ID
        $userId = Auth::id();

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
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create visit. Please try again.'], 500);
        }
    }
}
