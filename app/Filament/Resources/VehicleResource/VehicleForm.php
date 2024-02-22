<?php 

namespace App\Filament\Resources\VehicleResource;

use Filament\Forms;
use Filament\Forms\Form;
use App\Filament\Resources\ContactResource\RelationManagers\VehiclesRelationManager;

class VehicleForm extends Form
{
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
                    Forms\Components\TextInput::make('license_plate')
                        ->label('Placas')
                        ->extraInputAttributes(['style' => 'text-transform:uppercase;']),
                ]),
                Forms\Components\Section::make('Datos adicionales')
                ->schema([
                    Forms\Components\Textarea::make('notes')
                        ->label('Notas')
                        ->rows(5),
                ]),
            ]);
    }
}