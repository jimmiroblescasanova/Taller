<?php

namespace App\Filament\Resources\EstimateResource\Pages;

use App\Traits\ShouldRedirectToIndex;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\EstimateResource;

class CreateEstimate extends CreateRecord
{
    use ShouldRedirectToIndex;
    
    protected static string $resource = EstimateResource::class;
}
