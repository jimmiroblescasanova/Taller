<?php

namespace App\Filament\Exports;

use App\Models\Order;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Models\Export;

class SalesReportExporter extends Exporter
{
    protected static ?string $model = Order::class;

    // protected static ?string $modelLabel = 'Exportar reporte';

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('created_at')
                ->label('Fecha'),
            ExportColumn::make('agent.name')
                ->label('Agente'),
            ExportColumn::make('subtotal')
                ->label('Subtotal'),
            ExportColumn::make('tax')
                ->label('IVA'),
            ExportColumn::make('total')
                ->label('Total'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'El reporte esta listo, ' . number_format($export->successful_rows) . ' ' . str('fila')->plural($export->successful_rows) . ' exportadas.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('fila')->plural($failedRowsCount) . ' ha fallado.';
        }

        return $body;
    }
}
