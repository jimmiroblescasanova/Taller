<?php

namespace App\Filament\Resources\VehicleResource\Pages;

use Filament\Actions;
use App\Traits\ShouldRedirectToIndex;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\VehicleResource;

class CreateVehicle extends CreateRecord
{
    use ShouldRedirectToIndex;
    
    protected static string $resource = VehicleResource::class;
}
