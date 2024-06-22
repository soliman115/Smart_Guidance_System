<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Place;
use App\Models\Service;
use App\Models\User;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AdminStatisticsService
{
    public function getAdminStatistics(): array
    {
        // Total number of users
        $totalUsers = User::count();

        // Total number of employees
        $totalEmployees = Employee::count();

        // Total number of services
        $totalServices = Service::count();

        // Top popular locations with visit counts
        $topPopularLocations = $this->getTopPopularLocations();

        // Total number of visits
        $totalVisits = Visit::count();

        // New users over different time periods
        $newUsersAllTime = $this->getNewUsersOverTime();
        $newUsersLastMonth = $this->getNewUsersOverTime(now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth());
        $newUsersLastSixMonths = $this->getNewUsersOverTime(now()->subMonths(6)->startOfMonth());

        // Visits over different time periods
        $allTimeVisits = $this->getVisitsOverTime();
        $lastMonthVisits = $this->getVisitsOverTime(now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth());
        $lastSixMonthsVisits = $this->getVisitsOverTime(now()->subMonths(6)->startOfMonth());

        // Statistics data into an array
        return [
            'totalUsers' => $totalUsers,
            'totalEmployees' => $totalEmployees,
            'totalServices' => $totalServices,
            'totalVisits' =>  $totalVisits,
            'topPopularLocations' =>  $topPopularLocations,
            'newUsersAllTime' => $newUsersAllTime,
            'newUsersLastMonth' => $newUsersLastMonth,
            'newUsersLastSixMonths' => $newUsersLastSixMonths,
            'allTimeVisits' => $allTimeVisits,
            'lastMonthVisits' => $lastMonthVisits,
            'lastSixMonthsVisits' => $lastSixMonthsVisits,
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

    private function getNewUsersOverTime(Carbon $startDate = null, Carbon $endDate = null): Collection
    {
        // Query to select date of creation and count of users grouped by date
        $query = User::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('count(*) as users_count')
        )
            ->groupBy('date')
            ->orderBy('date');

        // Add conditions for the specified time period if provided
        if ($startDate !== null && $endDate !== null) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate !== null) {
            $query->where('created_at', '>=', $startDate);
        }

        // Execute the query and return the results
        return $query->get();
    }

    private function getVisitsOverTime(Carbon $startDate = null, Carbon $endDate = null): Collection
    {
        // Query to select date of visit, count of distinct users, and total visits grouped by date
        $query = Visit::select(
            DB::raw('DATE(visited_at) as date'),
            DB::raw('COUNT(DISTINCT user_id) as total_users'),
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
