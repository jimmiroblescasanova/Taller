<?php

namespace App\Filament\Resources\EstimateResource\Pages;

use Filament\Actions;
use App\Enums\EstimateStatusEnum;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\EstimateResource;

class ListEstimates extends ListRecords
{
    protected static string $resource = EstimateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'disponibles' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', EstimateStatusEnum::AVAILABLE)),
            'completadas' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', EstimateStatusEnum::ORDERED)),
            'todos' => Tab::make(),
        ];
    }
}
