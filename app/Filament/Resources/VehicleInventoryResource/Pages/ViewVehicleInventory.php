<?php

namespace App\Filament\Resources\VehicleInventoryResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\VehicleInventoryResource;

class ViewVehicleInventory extends ViewRecord
{
    protected static string $resource = VehicleInventoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('back')
                ->label('Ir atrás')
                ->color('gray')
                ->url(static::getResource()::getUrl()),
        ];
    }
}
