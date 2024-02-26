<?php 

namespace App\Filament\Resources\VehicleInventoryResource;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class VehicleInventoryForm extends Form
{
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
}