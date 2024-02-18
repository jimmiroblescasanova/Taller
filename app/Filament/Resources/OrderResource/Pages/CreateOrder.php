<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Traits\ShouldRedirectToIndex;
use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    use ShouldRedirectToIndex;
    
    protected static string $resource = OrderResource::class;
}
