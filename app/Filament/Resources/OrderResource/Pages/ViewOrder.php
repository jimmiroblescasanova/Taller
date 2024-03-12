<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Models\Order;
use Filament\Actions;
use Illuminate\Support\Facades\Mail;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\OrderResource;
use App\Filament\Resources\VehicleInventoryResource;
use App\Mail\OrderCreated;

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

            Actions\Action::make('pdf')
                ->label('Ver PDF')
                ->url(fn (Order $record) => route('pdf.order.stream', $record))
                ->openUrlInNewTab(),

            Actions\Action::make('sendMail')
                ->label('Enviar por correo')
                ->action(function (Order $record) {
                    if (is_null($record->contact->email)) {
                        Notification::make()
                        ->title('El contacto no tiene email')
                        ->warning()
                        ->send();
            
                        $this->halt();
                    }
                    // TODO: change the admin email to client
                    Mail::to('admin@admin.com')->send(new OrderCreated($record));
            
                    return Notification::make()
                    ->title('Email enviado')
                    ->success()
                    ->send();
                }),

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
