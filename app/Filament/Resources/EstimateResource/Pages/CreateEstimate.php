<?php

namespace App\Filament\Resources\EstimateResource\Pages;

use App\Mail\EstimateCreated;
use Illuminate\Support\Facades\Mail;
use App\Traits\ShouldRedirectToIndex;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\EstimateResource;

class CreateEstimate extends CreateRecord
{
    use ShouldRedirectToIndex;
    
    protected static string $resource = EstimateResource::class;

    //TODO: send email to contacto instead admin
    protected function afterCreate(): void
    {
        $estimate = $this->getRecord();

        dispatch(function () use ($estimate) {
            Mail::to('admin@admin.com')->send(new EstimateCreated($estimate));
        })->afterResponse();
    }
}
