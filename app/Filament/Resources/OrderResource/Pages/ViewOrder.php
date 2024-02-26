<?php

namespace App\Filament\Resources\OrderResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\OrderResource;
use App\Filament\Resources\VehicleInventoryResource;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('viewInventory')
                ->label('Ver inventario')
                ->url(fn (): string => VehicleInventoryResource::getUrl('view', ['record' => $this->record->inventory]))
                ->visible(fn (): bool => $this->record->inventory()->exists()),

            Actions\EditAction::make()
                ->hidden(fn () => $this->record->trashed()),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),

            Actions\Action::make('back')
                ->label('Ir atrás')
                ->color('gray')
                ->url(static::getResource()::getUrl()),
        ];
    }
}
