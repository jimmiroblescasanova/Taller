<?php

namespace App\Models;

use App\Casts\MoneyCast;
use App\Enums\ProductType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    protected $casts = [
        'type'    => ProductType::class,
        'price_1' => MoneyCast::class,
        'price_2' => MoneyCast::class,
        'price_3' => MoneyCast::class,
        'price_4' => MoneyCast::class,
        'price_5' => MoneyCast::class,
        'price_6' => MoneyCast::class,
        'price_7' => MoneyCast::class,
        'price_8' => MoneyCast::class,
        'price_9' => MoneyCast::class,
        'price_10' => MoneyCast::class,
    ];

    public function scopeWithInventory(Builder $query): void
    {
        $query->where('inventory', '>=', 1);
    }

    public function scopeWithoutInventory(Builder $query): void
    {
        $query->where('inventory', 0);
    }

    public function scopeTypeProduct(Builder $query): void
    {
        $query->where('type', 1);
    }

    public function scopeTypeService(Builder $query): void
    {
        $query->where('type', 3);
    }
}
