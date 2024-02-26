<?php

namespace App\Filament\Pages;

use Filament\Tables;
use App\Models\Order;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;

class OrderQueue extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $title = 'Ordenes en Proceso';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.order-queue';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (Order $order) => $order->where('ended_at', null))
            ->poll('10s')
            ->groups([
                Tables\Grouping\Group::make('station.name')
                    ->label('Plataforma')
            ])
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->date(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Descripción'),
                Tables\Columns\TextColumn::make('vehicle.license_plate')
                    ->label('Placas'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge(),
                Tables\Columns\TextColumn::make('started_at')
                    ->label('Tiempo de trabajo')    
                    ->since(),
            ]);
    }
}
