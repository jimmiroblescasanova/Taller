<?php

namespace App\Filament\Widgets;

use Illuminate\Support\Facades\Cache;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Cotizaciones de hoy', Cache::get('today_estimates_count', 0))
                ->chart(Cache::get('week_estimates_graph'))
                ->color('warning'),
                
            Stat::make('Ordenes de hoy', Cache::get('today_orders_count', 0))
                ->chart(Cache::get('week_orders_graph'))
                ->color('info'),

            Stat::make('Ingresos del día', function() {
                $money = Cache::get('today_incomes', 0) / 100;

                return "$ " . number_format($money, 2);
                })
                ->chart(Cache::get('week_orders_total'))
                ->color('success'),
        ];
    }
}
