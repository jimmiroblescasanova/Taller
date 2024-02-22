<?php

namespace App\Filament\Resources\VehicleResource\Pages;

use Filament\Actions;
use App\Traits\ShouldRedirectToIndex;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\VehicleResource;

class EditVehicle extends EditRecord
{
    use ShouldRedirectToIndex;
    
    protected static string $resource = VehicleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
