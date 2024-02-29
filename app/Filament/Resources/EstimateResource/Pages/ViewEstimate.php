<?php

namespace App\Filament\Resources\EstimateResource\Pages;

use Filament\Actions;
use App\Models\Estimate;
use App\Mail\EstimateCreated;
use Illuminate\Support\Facades\Mail;
use Filament\Notifications\Notification;
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

            Actions\Action::make('print')
                ->label('Ver PDF')
                ->color('danger')
                ->url(fn (Estimate $record) => route('pdf.estimate.stream', $record))
                ->openUrlInNewTab(),
            
            Actions\Action::make('sendMail')
                ->label('Enviar email')
                ->color('gray')
                ->action(function (Estimate $record) {
                    if (is_null($record->contact->email)) {
                        Notification::make()
                        ->title('El contacto no tiene email')
                        ->warning()
                        ->send();
            
                        $this->halt();
                    }
            
                    Mail::to('admin@admin.com')->send(new EstimateCreated($record));
            
                    return Notification::make()
                    ->title('Email enviado')
                    ->success()
                    ->send();
                }),

            Actions\Action::make('back')
                ->label('Ir atrás')
                ->color('gray')
                ->url(static::getResource()::getUrl()),
        ];
    }
}
