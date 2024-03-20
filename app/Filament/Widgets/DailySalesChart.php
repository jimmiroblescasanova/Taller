<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\Estimate;
use Flowframe\Trend\Trend;
use Illuminate\Support\Carbon;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

class DailySalesChart extends ChartWidget
{
    protected static ?string $heading = 'Cotizado y Vendido';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $pollingInterval = null;

    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $order = Trend::model(Order::class)
            ->between(
                start:now()->subDays(7)->startOfDay(),
                end:now()->endOfDay()
            )
            ->perDay()
            ->sum('total');

        $estimate = Trend::model(Estimate::class)
            ->between(
                start:now()->subDays(10)->startOfDay(),
                end:now()->endOfDay()
            )
            ->perDay()
            ->sum('total');

        return [
            'datasets' => [
                [
                    'label' => 'Ordenes',
                    'data' => $order->map(fn (TrendValue $value) => $value->aggregate / 100),
                    'borderColor' => 'rgb(75, 192, 192)',
                ],
                [
                    'label' => 'Cotizaciones',
                    'data' => $estimate->map(fn (TrendValue $value) => $value->aggregate / 100),
                ]
            ],
            'labels' => $order->map(fn (TrendValue $value) => Carbon::create($value->date)->format('d-M') ),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
