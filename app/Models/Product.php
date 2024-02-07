<?php

namespace App\Models;

use App\Casts\MoneyCast;
use App\Enums\ProductType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

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
}
