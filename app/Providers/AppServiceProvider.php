<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Order;
use App\Models\Estimate;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();
        
        Gate::before(function (User $user, string $ability) {
            return $user->isSuperAdmin() ? true: null;
        });

        // TODO: change the value of time when production
        Cache::putMany([
            'today_estimates_count' => Estimate::whereDay('created_at', Carbon::now()->format('d'))->count(),
            'today_orders_count' => Order::whereDay('created_at', Carbon::now()->format('d'))->count(),
            'today_incomes' => Order::whereDay('created_at', Carbon::now()->format('d'))->sum('total'),
        ], 10);

        Cache::putMany([
            'week_estimates_graph' => Estimate::getLastWeekCountsPerDay(),
            'week_orders_graph' => Order::getLastWeekCountsPerDay(),
            'week_orders_total' => Order::getWeekTotalsPerDay(),
        ], 10);
    }
}
