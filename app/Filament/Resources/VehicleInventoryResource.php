<?php

namespace App\Filament\Resources;

use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\VehicleInventory;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\VehicleInventoryResource\Pages;
use App\Filament\Resources\VehicleInventoryResource\RelationManagers;
use App\Filament\Resources\VehicleInventoryResource\VehicleInventoryForm;
use App\Filament\Resources\VehicleInventoryResource\VehicleInventoryInfolist;

class VehicleInventoryResource extends Resource
{
    protected static ?string $model = VehicleInventory::class;

    protected static ?string $pluralModelLabel = 'Inventarios de Vehiculo';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return VehicleInventoryForm::form($form);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return VehicleInventoryInfolist::infolist($infolist);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'DESC')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha')
                    ->date(),
                Tables\Columns\TextColumn::make('order_id')
                    ->label('# Orden')
                    ->searchable(),
                Tables\Columns\TextColumn::make('specialist.name')
                    ->label('Responsable')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('vehicle.license_plate')
                    ->label('Placas')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('specialist')
                    ->label('Especialista')
                    ->multiple()
                    ->relationship(name: 'specialist', titleAttribute: 'name')
                    ->preload()
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListVehicleInventories::route('/'),
            'view' => Pages\ViewVehicleInventory::route('/{record}'),
            'edit' => Pages\EditVehicleInventory::route('/{record}/edit'),
        ];
    }
    
    public static function canCreate(): bool
    {
        return false;
    }

}
