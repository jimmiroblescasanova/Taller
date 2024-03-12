<?php

namespace App\Filament\Resources;

use Filament\Tables;
use App\Models\Order;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Enums\OrderStatusEnum;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Summarizers\Sum;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\OrderForm;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\OrderResource\OrderInfolist;
use App\Filament\Resources\OrderResource\RelationManagers;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    
    protected static ?string $modelLabel = 'orden';
    protected static ?string $pluralModelLabel = 'ordenes';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return OrderForm::form($form);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return OrderInfolist::infolist($infolist);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->deferLoading()
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('#'),
                Tables\Columns\TextColumn::make('contact.full_name')
                    ->label('Contacto / Descripción')
                    ->description(fn (Order $record): string => $record->title)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->money('MXN')
                    ->searchable()
                    ->sortable()
                    ->alignEnd()
                    ->summarize(
                        Sum::make()
                        ->label('TOTAL')
                        ->money('MXN', divideBy: 100),
                    ),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->date()
                    ->searchable()
                    ->sortable()
                    ->alignEnd(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->alignCenter(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->multiple()
                    ->options(OrderStatusEnum::class),
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('inventory')
                        ->label('Inventario')
                        ->icon('heroicon-o-photo')
                        ->action(function (Order $record) {
                            $inventory = $record->inventory()->create([
                                'vehicle_id' => $record->vehicle_id,
                            ]);

                            return redirect(VehicleInventoryResource::getUrl('edit', ['record' => $inventory]));
                        })
                        ->requiresConfirmation()
                        ->hidden(fn(Order $record): bool => $record->inventory()->exists() || $record->status == OrderStatusEnum::INCOMPLETE),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                ])
                ->link()
                ->label('Opciones')
                ->hidden(fn (Order $record) => $record->trashed()),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }    

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
