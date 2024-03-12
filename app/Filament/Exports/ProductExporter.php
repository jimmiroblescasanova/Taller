<?php

namespace App\Filament\Exports;

use App\Models\Product;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Models\Export;

class ProductExporter extends Exporter
{
    protected static ?string $model = Product::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('code')
                ->label('Codigo'),
            ExportColumn::make('name')
                ->label('Descripcion'),
            ExportColumn::make('type')
                ->label('Tipo')
                ->state(function (Product $record) {
                    return $record->type == 1 ? 'producto' : 'servicio';
                }),
            ExportColumn::make('status')
                ->label('Estado')
                ->state(fn (Product $record) => $record->status ? 'activo' : 'inactivo'),
            ExportColumn::make('um')
                ->label('Unidad de Medida'),
            ExportColumn::make('inventory')
                ->label('Existencia'),
            ExportColumn::make('cl_1')
                ->label('Clasificacion 1'),
            ExportColumn::make('cl_2')
                ->label('Clasificacion 2'),
            ExportColumn::make('cl_3')
                ->label('Clasificacion 3'),
            ExportColumn::make('price_1')
                ->label('Precio 1'),
            ExportColumn::make('price_2')
                ->label('Precio 2'),
            ExportColumn::make('price_3')
                ->label('Precio 3'),
            ExportColumn::make('price_4')
                ->label('Precio 4'),
            ExportColumn::make('price_5')
                ->label('Precio 5'),
            ExportColumn::make('price_6')
                ->label('Precio 6'),
            ExportColumn::make('price_7')
                ->label('Precio 7'),
            ExportColumn::make('price_8')
                ->label('Precio 8'),
            ExportColumn::make('price_9')
                ->label('Precio 9'),
            ExportColumn::make('price_10')
                ->label('Precio 10'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Se han procesado ' . number_format($export->successful_rows) . ' ' . str('fila')->plural($export->successful_rows) . ' de productos.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('fila')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
