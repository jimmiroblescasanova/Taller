<?php 

namespace App\Traits;

use Illuminate\Support\Carbon;

trait GetStats
{
    public static function getLastWeekCountsPerDay()
    {
        $last_week = Carbon::now()->subDays(7)->toDateString();
        
        return static::query()
        ->whereDate('created_at', '>=', $last_week)
        ->selectRaw('DATE(created_at) as day, COUNT(*) as count')
        ->groupBy('day')
        ->get()
        ->pluck('count')
        ->toArray();
    }

    public static function getWeekTotalsPerDay()
    {
        $last_week = Carbon::now()->subDays(7)->toDateString();
        
        return static::query()
        ->whereDate('created_at', '>=', $last_week)
        ->selectRaw('DATE(created_at) as day, SUM(total) as sum')
        ->groupBy('day')
        ->get()
        ->pluck('sum')
        ->toArray();
    }
}
