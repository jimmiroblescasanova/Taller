<?php

namespace App\Filament\Resources;

use Filament\Tables;
use App\Models\Vehicle;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\VehicleResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\VehicleResource\VehicleForm;
use App\Filament\Resources\VehicleResource\VehicleInfolist;
use App\Filament\Resources\VehicleResource\RelationManagers;

class VehicleResource extends Resource
{
    protected static ?string $model = Vehicle::class;

    protected static ?string $modelLabel = 'vehículo';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Catalogos';

    public static function form(Form $form): Form
    {
        return VehicleForm::form($form);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return VehicleInfolist::infolist($infolist);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('model')
                    ->label('Modelo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('vehicle_type.name')->label('Tipo'),
                Tables\Columns\TextColumn::make('vehicle_brand.name')->label('Marca'),
                Tables\Columns\TextColumn::make('license_plate')
                    ->label('Placas')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contact.full_name')
                    ->label('Contacto')
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('vehicle_type')
                    ->label('Tipo')
                    ->multiple()
                    ->relationship('vehicle_type', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('vehicle_brand')
                    ->label('Marca')
                    ->multiple()
                    ->relationship('vehicle_brand', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                //
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
            'index' => Pages\ListVehicles::route('/'),
            'create' => Pages\CreateVehicle::route('/create'),
            'view' => Pages\ViewVehicle::route('/{record}'),
            'edit' => Pages\EditVehicle::route('/{record}/edit'),
        ];
    }    
}
