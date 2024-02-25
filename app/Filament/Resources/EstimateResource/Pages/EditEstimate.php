<?php

namespace App\Filament\Resources\EstimateResource\Pages;

use Filament\Actions;
use App\Models\Estimate;
use App\Enums\EstimateStatusEnum;
use App\Traits\ShouldRedirectToIndex;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\EstimateResource;

class EditEstimate extends EditRecord
{
    use ShouldRedirectToIndex;
    
    protected static string $resource = EstimateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make()
                ->before(function (Estimate $record) {
                    $record->update([
                        'status' => EstimateStatusEnum::CANCELLED,
                    ]);
                }),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
