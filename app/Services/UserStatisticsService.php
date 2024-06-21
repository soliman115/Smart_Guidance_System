<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Service;
use App\Models\User;
use App\Models\Place;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class UserStatisticsService
{
    public function getUserStatistics(int $userId): array
    {
        // Total number of locations
        $locations = Place::count();

        // Total number of employees
        $totalEmployees = Employee::count();

        // Total number of services
        $totalServices = Service::count();

        // Retrieve the authenticated user and their visit history
        $user = User::with('visits')->find($userId);
        $userVisitHistory = $user->visits ?? [];

        // Retrieve top popular locations with visit counts
        $topPopularLocations = $this->getTopPopularLocations();

        // Retrieve visit statistics for different time periods
        $allTimeVisits = $this->getVisitsOverTime($userId);
        $lastMonthVisits = $this->getVisitsOverTime($userId, now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth());
        $lastSixMonthsVisits = $this->getVisitsOverTime($userId, now()->subMonths(6)->startOfMonth());

        // Prepare statistics data
        return [
            'userId' => $userId,
            'locations' => $locations,
            'totalEmployees' => $totalEmployees,
            'totalServices' => $totalServices,
            'topPopularLocations' => $topPopularLocations,
            'userVisitHistory' => $userVisitHistory,
            'allTimeVisits' => $allTimeVisits,
            'lastMonthVisits' => $lastMonthVisits,
            'lastSixMonthsVisits' => $lastSixMonthsVisits
        ];
    }

    private function getTopPopularLocations(): Collection
    {
        // Query to fetch top popular locations with visit counts
        return Place::select('id', 'name')
            ->withCount('visits')
            ->orderByDesc('visits_count')
            ->limit(7)
            ->get();
    }

    private function getVisitsOverTime(int $userId, Carbon $startDate = null, Carbon $endDate = null): Collection
    {
        // Query to select date of visit and total visits grouped by date
        $query = Visit::where('user_id', $userId)
            ->select(
                DB::raw('DATE(visited_at) as date'),
                DB::raw('COUNT(*) as visits_count')
            )
            ->groupBy('date')
            ->orderBy('date');

        // Add conditions for the specified time period if provided
        if ($startDate !== null && $endDate !== null) {
            $query->whereBetween('visited_at', [$startDate, $endDate]);
        } elseif ($startDate !== null) {
            $query->where('visited_at', '>=', $startDate);
        }

        // Execute the query and return the results
        return $query->get();
    }
}
