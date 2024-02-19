<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Infolists;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\VehicleInventory;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\VehicleInventoryResource\Pages;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use App\Filament\Resources\VehicleInventoryResource\RelationManagers;
use Njxqlus\Filament\Components\Infolists\LightboxSpatieMediaLibraryImageEntry;

class VehicleInventoryResource extends Resource
{
    protected static ?string $model = VehicleInventory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                ->schema([
                    SpatieMediaLibraryFileUpload::make('images')
                    ->label('Fotos del estado del vehiculo')
                    ->multiple()
                    ->conversion('preview')
                    ->image()
                ]),
                Forms\Components\Section::make()
                ->schema([
                    Forms\Components\Select::make('specialist_id')
                        ->label('Responsable')
                        ->relationship(name: 'specialist', titleAttribute: 'name')
                        ->searchable()
                        ->preload()
                        ->selectablePlaceholder(false)
                        ->required(),
                    Forms\Components\Textarea::make('notes')
                        ->label('Notas adicionales')
                        ->rows(5),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Grid::make()
                ->schema([
                    Infolists\Components\Section::make('Fotos del vehiculo')
                    ->icon('heroicon-m-wrench-screwdriver')
                    ->schema([
                        LightboxSpatieMediaLibraryImageEntry::make('images')
                        ->label('Imagenes')
                        ->conversion('preview')
                    ])
                    ->columnSpan(2),

                    Infolists\Components\Section::make('Datos del vehiculo')
                    ->icon('heroicon-m-identification')
                    ->schema([
                        Infolists\Components\TextEntry::make('specialist.name')
                            ->label('Responsable'),
                        Infolists\Components\TextEntry::make('vehicle.license_plate'),
                        Infolists\Components\TextEntry::make('notes'),
                    ])
                    ->columnSpan(1),
                ])
                ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_id')
                ->label('# Orden'),
                Tables\Columns\TextColumn::make('specialist.name')
                ->label('Responsable'),
                Tables\Columns\TextColumn::make('vehicle.license_plate')
                ->label('Placas'),
                Tables\Columns\TextColumn::make('created_at')
                ->label('Fecha')
                ->date(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            // 'create' => Pages\CreateVehicleInventory::route('/create'),
            'view' => Pages\ViewVehicleInventory::route('/{record}'),
            'edit' => Pages\EditVehicleInventory::route('/{record}/edit'),
        ];
    }
    
    public static function canCreate(): bool
    {
        return false;
    }

}
