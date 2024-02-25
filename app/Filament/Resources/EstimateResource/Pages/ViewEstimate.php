<?php

namespace App\Filament\Resources\EstimateResource\Pages;

use Filament\Actions;
use App\Models\Estimate;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\EstimateResource;

class ViewEstimate extends ViewRecord
{   
    protected static string $resource = EstimateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->hidden(fn (Estimate $record) => $record->order()->exists()),
            Actions\Action::make('back')
                ->label('Ir atrás')
                ->color('gray')
                ->url(static::getResource()::getUrl()),
        ];
    }
}
