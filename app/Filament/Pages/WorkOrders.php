<?php

namespace App\Filament\Pages;

use Filament\Tables;
use App\Models\Order;
use Filament\Pages\Page;
use Filament\Tables\Table;
use App\Enums\OrderStatusEnum;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;

class WorkOrders extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $title = 'Procesar Ordenes';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.work-orders';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (Order $order) => $order->where('ended_at', null))
            ->groups([
                Tables\Grouping\Group::make('station.name')
                    ->label('Plataforma')
            ])
            ->defaultGroup('station.name')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->date(),
                Tables\Columns\TextColumn::make('id')
                    ->label('# Orden'),
                Tables\Columns\TextColumn::make('title')
                    ->label('Descripción'),
                Tables\Columns\TextColumn::make('vehicle.license_plate')
                    ->label('Placas'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge(),
                Tables\Columns\TextColumn::make('specialist.name')
                    ->label('Mecanico'),
                Tables\Columns\TextColumn::make('started_at')
                    ->label('Tiempo de inicio')
                    ->since(),
            ])
            ->actions([
                Tables\Actions\Action::make('start')
                    ->label('Iniciar')
                    ->requiresConfirmation()
                    ->action(function(Order $record) {
                        $record->update([
                            'status' => OrderStatusEnum::PROCESING,
                            'started_at' => NOW(),
                        ]);
                    })
                    ->visible(fn (Order $record) => $record->status === OrderStatusEnum::PENDING),
                Tables\Actions\Action::make('complete')
                    ->label('Terminar')
                    ->requiresConfirmation()
                    ->action( function(Order $record) {
                        $record->update([
                            'status' => OrderStatusEnum::COMPLETED,
                            'ended_at' => NOW(),
                        ]);
                    })
                    ->visible(fn (Order $record) => $record->status === OrderStatusEnum::PROCESING),
            ]);
    }
}
