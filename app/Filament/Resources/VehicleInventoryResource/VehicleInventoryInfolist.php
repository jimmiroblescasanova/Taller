<?php 

namespace App\Filament\Resources\VehicleInventoryResource;

use Filament\Infolists;
use Filament\Infolists\Infolist;
use Njxqlus\Filament\Components\Infolists\LightboxSpatieMediaLibraryImageEntry;

class VehicleInventoryInfolist extends Infolist
{
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
                    Infolists\Components\TextEntry::make('vehicle.license_plate')
                        ->label('Placas'),
                    Infolists\Components\TextEntry::make('notes')
                        ->label('Notas'),
                ])
                ->columnSpan(1),
            ])
            ->columns(3),
        ]);
    }
}