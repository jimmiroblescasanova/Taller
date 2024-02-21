<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Models\Order;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\OrderResource;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('viewInventory')
                ->label('Ver inventario')
                ->url(route('filament.admin.resources.vehicle-inventories.view', $this->record->inventory->id))
                ->visible(fn (): bool => $this->record->inventory()->exists()),
            Actions\EditAction::make(),

            Actions\Action::make('back')
                ->label('Atrás')
                ->url(url()->previous()),
        ];
    }
}
