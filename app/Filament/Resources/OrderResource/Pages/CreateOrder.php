<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Mail\OrderCreated;
use Illuminate\Support\Facades\Mail;
use App\Traits\ShouldRedirectToIndex;
use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    use ShouldRedirectToIndex;
    
    protected static string $resource = OrderResource::class;

    protected function afterCreate(): void
    {
        $order = $this->getRecord();

        dispatch(function () use ($order) {
            Mail::to('admin@admin.com')->send(new OrderCreated($order));
        })->afterResponse();
    }
}
