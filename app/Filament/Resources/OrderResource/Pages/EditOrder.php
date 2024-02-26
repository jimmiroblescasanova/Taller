<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Models\Order;
use Filament\Actions;
use App\Enums\OrderStatusEnum;
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
            ->before(function (Order $record) {
                $record->update([
                    'status' => OrderStatusEnum::CANCELLED,
                ]);
            }),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if ($record['status'] == OrderStatusEnum::INCOMPLETE) {
            $data['status'] = OrderStatusEnum::PENDING;
        }

        $record->update($data);

        return $record;
    }
}
