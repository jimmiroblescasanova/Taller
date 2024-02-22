<?php

namespace App\Filament\Resources\ContactResource;

use Filament\Infolists;
use Filament\Infolists\Infolist;

class ContactInfolist extends Infolist
{
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Información general')
                ->icon('heroicon-o-identification')
                ->schema([
                    Infolists\Components\TextEntry::make('full_name')
                        ->label('Nombre completo')
                        ->columnSpanFull(),
                    Infolists\Components\TextEntry::make('email')
                        ->label('Correo electrónico'),
                    Infolists\Components\TextEntry::make('phone')
                        ->label('Teléfono'),
                ])
                ->collapsible()
                ->persistCollapsed()
                ->columns(2)
                ->columnSpan(2),

                Infolists\Components\Section::make('Información adicional')
                ->icon('heroicon-o-folder')
                ->schema([
                    Infolists\Components\TextEntry::make('channel')
                        ->label('Como nos conoció'),
                    Infolists\Components\TextEntry::make('price_list')
                        ->label('Lista de Precios'),
                    Infolists\Components\TextEntry::make('notes')
                        ->label('Notas'),
                ])
                ->collapsible()
                ->persistCollapsed()
                ->columnSpan(1),
            ])
            ->columns(3);
    }
}