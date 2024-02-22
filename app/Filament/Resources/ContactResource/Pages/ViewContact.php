<?php

namespace App\Filament\Resources\ContactResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\ContactResource;

class ViewContact extends ViewRecord
{
    protected static string $resource = ContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('back')
                ->label('Ir atrás')
                ->color('gray')
                ->url(url()->previous()),
        ];
    }
}
