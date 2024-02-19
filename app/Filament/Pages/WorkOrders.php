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

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.work-orders';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (Order $order) => $order->where('ended_at', null))
            ->defaultGroup('agent.name')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->date(),
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('started_at')->since(),
            ])
            ->actions([
                Tables\Actions\Action::make('start')
                    ->requiresConfirmation()
                    ->action(function(Order $record) {
                        $record->update([
                            'status' => OrderStatusEnum::PROCESING,
                            'started_at' => NOW(),
                        ]);
                    })
                    ->visible(fn (Order $record) => $record->status === OrderStatusEnum::PENDING),
                Tables\Actions\Action::make('complete')
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
