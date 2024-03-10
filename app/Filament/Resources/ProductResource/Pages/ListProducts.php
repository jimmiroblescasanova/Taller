<?php

namespace App\Filament\Resources\ProductResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProductResource;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make()
                ->label('Todos'),
            'products' => Tab::make()
                ->label('Productos')
                ->modifyQueryUsing(fn (Builder $query) => $query->typeProduct()),
            'services' => Tab::make()
                ->label('Servicios')
                ->modifyQueryUsing(fn (Builder $query) => $query->typeService()),
        ];
    }
}
