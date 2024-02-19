<?php

namespace App\Filament\Resources\VehicleInventoryResource\Pages;

use App\Filament\Resources\VehicleInventoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVehicleInventories extends ListRecords
{
    protected static string $resource = VehicleInventoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
