<?php

namespace App\Filament\Pages;

use Filament\Forms;
use App\Models\Order;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Tables\Filters;
use App\Enums\OrderStatusEnum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Summarizers\Sum;
use App\Filament\Exports\SalesReportExporter;
use Filament\Tables\Concerns\InteractsWithTable;

class SalesReport extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationGroup = 'Reportes';

    protected static ?string $title = 'Reporte de Ventas';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.orders-report';

    public function table(Table $table): Table
    {
        return $table
            ->query(Order::query())
            ->defaultSort(column:'id', direction:'DESC')
            ->filters(static::tableFilters(), layout: FiltersLayout::AboveContent)
            ->filtersFormColumns(4)
            ->columns(static::tableColumns())
            ->deferLoading()
            ->paginated([25, 50, 100, 'all']);
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\ExportAction::make()
                ->label('Exportar XLS')
                ->modalHeading('¿Comenzar la descarga del reporte?')
                ->icon('heroicon-o-arrow-down-tray')
                ->exporter(SalesReportExporter::class)
                ->columnMapping(false),
        ];
    }

    protected static function tableColumns(): array
    {
        return [
            TextColumn::make('id')
                ->label('#'),
            TextColumn::make('created_at')
                ->label('Fecha')
                ->date(),
            TextColumn::make('contact.full_name')
                ->label('Contacto'),
            TextColumn::make('agent.name')
                ->label('Agente'),
            TextColumn::make('subtotal')
                ->numeric(decimalPlaces:2)
                ->summarize(
                    Sum::make()->money('MXN', divideBy:100)
                )
                ->alignEnd(),
            TextColumn::make('tax')
                ->label('IVA')
                ->numeric(decimalPlaces:2)
                ->summarize(
                    Sum::make()->money('MXN', divideBy:100)
                )
                ->alignEnd(),
            TextColumn::make('total')
                ->numeric(decimalPlaces:2)
                ->summarize(
                    Sum::make()->money('MXN', divideBy:100)
                )
                ->alignEnd(),
        ];
    }

    protected static function tableFilters(): array
    {
        return [
            Filters\SelectFilter::make('status')
                ->label('Estado')
                ->options(OrderStatusEnum::class),

            Filters\SelectFilter::make('agent')
                ->label('Agente')
                ->relationship('agent', 'name'),
            
            Filters\Filter::make('created_at')
                ->label('Fecha')
                ->form([
                    Forms\Components\DatePicker::make('starting')
                        ->label('Fecha inicial')
                        ->default(now()),
                    Forms\Components\DatePicker::make('ending')
                        ->label('Fecha final'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['starting'], 
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                        )
                        ->when(
                            $data['ending'], 
                            fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                        );
                })
                ->columns(2)
                ->columnSpan(2),
        ];
    }
}
