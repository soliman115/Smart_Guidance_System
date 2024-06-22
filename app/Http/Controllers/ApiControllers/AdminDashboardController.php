<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Services\AdminStatisticsService;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Support\Facades\Log;

class AdminDashboardController extends Controller
{
    protected $adminStatisticsService;

    public function __construct(AdminStatisticsService $adminStatisticsService)
    {
        $this->adminStatisticsService = $adminStatisticsService;
    }

    public function getAdminStatistics(): JsonResponse
    {
        try {
            // Get admin statistics using the service
            $statistics = $this->adminStatisticsService->getAdminStatistics();

            return response()->json([
                'message' => 'Admin statistics retrieved successfully',
                'adminStatistics' => $statistics,
            ], 200);
        } catch (Exception $e) {
            Log::error('Failed to retrieve admin statistics', ['error' => $e->getMessage()]);
            // Handle any unexpected exceptions
            return response()->json(['error' => 'Failed to retrieve statistics. Please try again.'], 500);
        }
    }
}
