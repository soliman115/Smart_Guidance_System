<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Services\UserStatisticsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Log;


class UserDashboardController extends Controller
{
    protected $userStatisticsService;

    public function __construct(UserStatisticsService $userStatisticsService)
    {
        $this->userStatisticsService = $userStatisticsService;
    }

    public function getUserStatistics(): JsonResponse
    {
        try {
            // Get the authenticated user's ID
            $userId = Auth::id();

            // Get user statistics using the service
            $statistics = $this->userStatisticsService->getUserStatistics($userId);

            // Return the statistics data as JSON response
            return response()->json([
                'message' => 'User statistics retrieved successfully',
                'userStatistics' => $statistics,
            ], 200);
        } catch (Exception $e) {
            Log::error('Failed to retrieve user statistics', ['error' => $e->getMessage()]);
            // Handle any unexpected exceptions
            return response()->json(['error' => 'Failed to retrieve statistics. Please try again.'], 500);
        }
    }
}
