<?php

namespace App\Filament\Exports;

use App\Models\Order;
use Illuminate\Support\Carbon;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Models\Export;

class CommissionReportExporter extends Exporter
{
    protected static ?string $model = Order::class;

    /**
     * Define the columns to show on the exported file
     *
     * @return array
     */
    public static function getColumns(): array
    {
        return [
            ExportColumn::make('agent.name')
                ->label('Agente'),
            ExportColumn::make('created_at')
                ->label('Fecha')
                ->formatStateUsing(function (string $state): string {
                    $date = Carbon::createFromDate($state);
                    return $date->format('d-m-Y');
                }),
            ExportColumn::make('id')
                ->label('Folio'),
            ExportColumn::make('total')
                ->label('Total'),
            ExportColumn::make('commission')
                ->label('Comision')
                ->state(function (Order $record): float {
                    return $record->total * ($record->agent->comision / 100);
                }),
        ];
    }

    /**
     * Returns the notification for the exporter
     *
     * @param Export $export
     * @return string
     */
    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'El reporte de comisiones esta completo para ' . number_format($export->successful_rows) . ' ' . str('fila')->plural($export->successful_rows) . ' exportadas.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
