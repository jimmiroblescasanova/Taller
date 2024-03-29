<?php

namespace App\Filament\Resources\ContactResource\Pages;

use Filament\Actions;
use App\Traits\ShouldRedirectToIndex;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\ContactResource;

class EditContact extends EditRecord
{
    use ShouldRedirectToIndex;
    
    protected static string $resource = ContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
