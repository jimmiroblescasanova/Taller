<?php

namespace App\Filament\Resources\ContactResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\VehicleResource;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Resources\VehicleResource\VehicleInfolist;

class VehiclesRelationManager extends RelationManager
{
    protected static string $relationship = 'vehicles';

    protected static ?string $icon = 'heroicon-o-truck';

    protected static ?string $title = 'Vehículos';

    protected static ?string $modelLabel = 'vehiculo';

    public function form(Form $form): Form
    {
        return VehicleResource::form($form);
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return VehicleInfolist::infolist($infolist);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('model')->label('Modelo'),
                Tables\Columns\TextColumn::make('vehicle_type.name')->label('Tipo'),
                Tables\Columns\TextColumn::make('vehicle_brand.name')->label('Marca'),
                Tables\Columns\TextColumn::make('year')->label('Año'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        $count = $ownerRecord->vehicles()->count();

        return $count > 0 ? $count : null;
    }
}
