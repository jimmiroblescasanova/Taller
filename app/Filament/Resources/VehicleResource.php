<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Vehicle;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\VehicleResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\VehicleResource\RelationManagers;
use App\Filament\Resources\ContactResource\RelationManagers\VehiclesRelationManager;

class VehicleResource extends Resource
{
    protected static ?string $model = Vehicle::class;

    protected static ?string $modelLabel = 'vehículo';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Catalogos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Datos principales de la unidad')
                ->columns(2)
                ->schema([
                    Forms\Components\Select::make('contact_id')
                        ->label('Contacto de la unidad')
                        ->relationship(name: 'contact', titleAttribute: 'full_name')
                        ->searchable()
                        ->optionsLimit(20)
                        ->required()
                        ->columnSpanFull()
                        ->hiddenOn(VehiclesRelationManager::class),
                    Forms\Components\Select::make('vehicle_type_id')
                        ->label('Tipo de unidad')
                        ->relationship(name: 'vehicle_type', titleAttribute: 'name')
                        ->searchable()
                        ->preload()
                        ->createOptionForm([
                            Forms\Components\TextInput::make('name')
                                ->label('Captura el tipo de unidad')
                                ->required(),
                        ])
                        ->required(),
                    Forms\Components\Select::make('vehicle_brand_id')
                        ->label('Marca de la unidad')
                        ->relationship(name: 'vehicle_brand', titleAttribute: 'name')
                        ->searchable()
                        ->preload()
                        ->createOptionForm([
                            Forms\Components\TextInput::make('name')
                                ->label('Captura la marca de la unidad')
                                ->required(),
                        ])
                        ->required(),
                    Forms\Components\TextInput::make('model')
                        ->label('Modelo')
                        ->required(),
                    Forms\Components\TextInput::make('year')
                        ->label('Año')
                        ->rules(['date_format:Y']),
                    Forms\Components\TextInput::make('color'),
                    Forms\Components\TextInput::make('license_plate')->label('Placas'),
                ]),
                Forms\Components\Section::make('Datos adicionales')
                ->schema([
                    Forms\Components\Textarea::make('notes')
                        ->label('Notas')
                        ->rows(5),
                ]),
            ]);
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
