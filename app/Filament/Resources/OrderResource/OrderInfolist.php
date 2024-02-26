<?php 

namespace App\Filament\Resources\OrderResource;

use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;

class OrderInfolist extends Infolist
{
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
        ->schema([
            Infolists\Components\Group::make()
            ->schema([
                static::getHeaderSection()
                    ->collapsible()
                    ->persistCollapsed()
                    ->columns(2),

                static::getItemsSection()
                    ->collapsible()
                    ->persistCollapsed()
                    ->compact(),
                
            ])->columnSpan(2),
            

            Infolists\Components\Group::make()
            ->schema([
                static::getServiceSection()
                    ->collapsible()
                    ->persistCollapsed()
                    ->columns(2),

                static::getVehicleSection()
                    ->collapsible()
                    ->persistCollapsed()
                    ->columns(2),
            ])
            ->columnSpan(1),

        ])->columns(3);
    }

    public static function getHeaderSection(): Section
    {
        return Section::make('Encabezado')
        ->icon('heroicon-o-identification')
        ->schema([
            Infolists\Components\TextEntry::make('title')
                ->label('Titulo de la orden')
                ->columnSpan(2),
            Infolists\Components\TextEntry::make('contact.name')
                ->label('Nombre del contacto'),
            Infolists\Components\TextEntry::make('contact.email')
                ->label('Correo electrónico'),

        ]);
    }

    public static function getItemsSection(): Section
    {
        return Section::make('Productos')
        ->icon('heroicon-o-shopping-bag')
        ->schema([
            Infolists\Components\RepeatableEntry::make('items')
            ->label('Detalle de los productos')
            ->schema([
                Infolists\Components\TextEntry::make('product.name')
                    ->label('Producto')
                    ->columnSpan(4),
                Infolists\Components\TextEntry::make('quantity')
                    ->label('Cantidad')
                    ->alignCenter(),
                Infolists\Components\TextEntry::make('price')
                    ->label('Precio')
                    ->money('MXN')
                    ->alignEnd(),
            ])
            ->columns(6)
            ->contained(false),

            Infolists\Components\Fieldset::make('Totales')
            ->schema([
                Infolists\Components\TextEntry::make('subtotal')
                    ->label('Subtotal')
                    ->money('MXN')
                    ->alignEnd(),
                Infolists\Components\TextEntry::make('tax')
                    ->label('Impuestos')
                    ->money('MXN')
                    ->alignEnd(),
                Infolists\Components\TextEntry::make('total')
                    ->label('Total')
                    ->money('MXN')
                    ->alignEnd(),
            ])
            ->columns(3),
        ]);
    }

    public static function getServiceSection(): Section
    {
        return Section::make('Información del servicio')
        ->icon('heroicon-o-folder-open')
        ->schema([
            Infolists\Components\TextEntry::make('status')
                ->label('Estado')
                ->badge(),
            Infolists\Components\TextEntry::make('station.name')
                ->label('Estación de trabajo'),
            Infolists\Components\TextEntry::make('agent.name')
                ->label('Agente asignado'),
        ]);
    }

    public static function getVehicleSection(): Section
    {
        return Section::make('Datos del vehiculo')
        ->icon('heroicon-o-truck')
        ->schema([
            Infolists\Components\TextEntry::make('vehicle.vehicle_type.name')
                ->label('Tipo de vehiculo'),
            Infolists\Components\TextEntry::make('vehicle.vehicle_brand.name')
                ->label('Marca'),
            Infolists\Components\TextEntry::make('vehicle.model')
                ->label('Modelo'),
            Infolists\Components\TextEntry::make('vehicle.color')
                ->label('Color'),
            Infolists\Components\TextEntry::make('vehicle.license_plate')
                ->label('Placas'),
        ]);
    }
}