<?php

namespace App\Filament\Resources\OrderResource\Pages;

use Filament\Actions;
use App\Enums\OrderStatus;
use App\Traits\ShouldRedirectToIndex;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\OrderResource;

class EditOrder extends EditRecord
{
    use ShouldRedirectToIndex;
    
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->before(function () {
                    $this->record->items()->delete();
                }),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if ($record['status'] == OrderStatus::INCOMPLETE) {
            $data['status'] = OrderStatus::PENDING;
        }

        $record->update($data);

        return $record;
    }
}
