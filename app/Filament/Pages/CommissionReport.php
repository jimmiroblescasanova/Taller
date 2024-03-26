<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use Filament\Actions;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Tables\Filters;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Concerns\InteractsWithTable;
use App\Filament\Exports\CommissionReportExporter;

class CommissionReport extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationGroup = 'Reportes';

    protected static ?string $title = 'Reporte de Comisiones';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.commission-report';

    /**
     * Shows the table on the page
     *
     * @param Table $table
     * @return Table
     */
    public function table(Table $table): Table
    {
        return $table
            ->query(Order::query())
            ->defaultSort('id', direction:'DESC')
            ->groups([
                Tables\Grouping\Group::make('agent.name')
                    ->label('Agente')
                    ->collapsible(),
                Tables\Grouping\Group::make('created_at')
                    ->label('Fecha')
                    ->date()
                    ->collapsible(),
            ])
            ->filters(static::tableFilters(), layout:FiltersLayout::AboveContent)
            ->filtersFormColumns(3)
            ->columns(static::getColumns())
            ->deferLoading();
    }

    /**
     * Get the table columns for the resource.
     *
     * @return array
     */
    protected static function getColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('agent.name')
                ->label('Agente'),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Fecha')
                ->date('d-m-Y'),
            Tables\Columns\TextColumn::make('id')
                ->label('Folio'),
            Tables\Columns\TextColumn::make('total')
                ->label('Total')
                ->money('MXN')
                ->alignEnd(),
            Tables\Columns\TextColumn::make('comission')
                ->state(function (Order $record): float{
                    return $record->total * ($record->agent->comision / 100);
                })
                ->money('MXN')
                ->alignEnd(),
        ];
    }

    /**
     * Get the table filters
     *
     * @return array
     */
    protected static function tableFilters(): array
    {
        return [
            Filters\SelectFilter::make('agent')
                ->label('Agente')
                ->multiple()
                ->relationship(name: 'agent', titleAttribute:'name')
                ->preload(),

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

    /**
     * Define the buttons on the header of the page
     *
     * @return array
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\ExportAction::make()
                ->label('Exportar reporte')
                ->icon('heroicon-o-arrow-down-tray')
                ->modalHeading('¿Comenzar la descarga del reporte?')
                ->exporter(CommissionReportExporter::class)
                ->columnMapping(false),
        ];
    }
}
