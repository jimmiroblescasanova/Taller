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

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.order-queue';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (Order $order) => $order->where('ended_at', null))
            ->poll('10s')
            ->defaultGroup('platform.name')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->date(),
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('started_at')->since(),
            ]);
    }
}
