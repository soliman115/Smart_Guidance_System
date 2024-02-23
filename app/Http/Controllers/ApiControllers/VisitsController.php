<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VisitsController extends Controller
{
    public function store(Request $request)
{
    // Get authenticated user ID
    $userId = Auth::id();
    if (!$userId) {
        return response()->json(['error' => 'Unauthorized access'], 401);
    }

    // Validate the request
    $request->validate([
        'place' => 'required|exists:Place,id',
    ]);

    // Get the selected location ID from the request
    $placeId = $request->input('place');

    // Record the visit
    //    $visit = Visit::create($request ->all());
    $visit = Visit::create([
        'user_id' => $userId,
        'location_id' => $placeId,
        'visited_at' => Carbon::now(), // Explicitly set timestamp
    ]);

//    return redirect()->route('dashboard')->with('success', 'Visit recorded successfully!');
    return response()->json([
        'message' => 'Visit created successfully',
        'visit' => $visit, // Include created visit data if needed
    ], 201);
}

}
