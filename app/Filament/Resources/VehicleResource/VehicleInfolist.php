<?php

namespace App\Filament\Resources\VehicleResource;

use Filament\Infolists;
use Filament\Infolists\Infolist;

class VehicleInfolist extends Infolist 
{
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Información general')
                ->icon('heroicon-o-truck')
                ->schema([
                    Infolists\Components\TextEntry::make('contact.full_name')
                        ->label('Nombre del contacto')
                        ->columnSpanFull(),
                    Infolists\Components\TextEntry::make('vehicle_type.name'),
                    Infolists\Components\TextEntry::make('vehicle_brand.name'),
                    Infolists\Components\TextEntry::make('model'),
                    Infolists\Components\TextEntry::make('year'),
                    Infolists\Components\TextEntry::make('color'),
                    Infolists\Components\TextEntry::make('license_plate'),
                ])
                ->collapsible()
                ->persistCollapsed()
                ->columns(3)
                ->columnSpan(2),

                Infolists\Components\Section::make('Información adicional')
                ->icon('heroicon-o-folder')
                ->schema([
                    Infolists\Components\TextEntry::make('notes')
                        ->label('Notas'),
                    Infolists\Components\TextEntry::make('updated_at')
                        ->label('Ult. actualización')
                        ->since(),
                ])
                ->collapsible()
                ->persistCollapsed()
                ->columnSpan(1),
            ])
            ->columns(3);
    }
}
