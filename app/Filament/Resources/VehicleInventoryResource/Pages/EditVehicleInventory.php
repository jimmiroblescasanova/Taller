<?php

namespace App\Filament\Resources\VehicleInventoryResource\Pages;

use Filament\Actions;
use App\Traits\ShouldRedirectToIndex;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\VehicleInventoryResource;

class EditVehicleInventory extends EditRecord
{
    use ShouldRedirectToIndex;
    
    protected static string $resource = VehicleInventoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
