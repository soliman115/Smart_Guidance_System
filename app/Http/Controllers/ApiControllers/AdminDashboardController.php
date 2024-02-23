<?php

namespace App\Http\Controllers\ApiControllers;

use App\Http\Controllers\Controller;
use App\Models\Place;
use App\Models\User;
use App\Models\Visit;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class AdminDashboardController extends Controller
{
    public function getStatistics()
    {
            $totalUsers = User::count();
            $activeUsersLastMonth = Visit::whereDate('visited_at', '>=', Carbon::now()->subDays(30))->distinct('user_id')->count();
            $locations = Place::withCount('visits')->get();
            $topPopularLocations = Place::withCount('visits')->orderBy('visits_count', 'desc')->limit(7)->get();
            $totalVisits = Visit::count();
            $newUsersPerDay = User::select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as users_count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            // Visits over time (daily data for past 30 days)
            $visitsPerDay = Visit::select(
                DB::raw('DATE(visited_at) as date'),
                DB::raw('COUNT(DISTINCT user_id) as total_users'),
                DB::raw('count(*) as visits_count')
            )
            ->where('visited_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();


        $statistics = [
                    'totalUsers' => $totalUsers,
                    'activeUsersLastMonth' => $activeUsersLastMonth,
                    'locations' => $locations,
                    'topPopularLocations' =>  $topPopularLocations,
                    'totalVisits' =>  $totalVisits,
                    'newUsersPerDay' => $newUsersPerDay,
                    'visitsPerDay' => $visitsPerDay,
                ];

//        return view('statistics', compact('statistics'));
        return response()->json([
            'message' => 'ok',
            'statistics' => $statistics,
        ], 200);
    }

}
