<?php

namespace App\Models;

use App\Casts\MoneyCast;
use App\Traits\GetStats;
use App\Enums\OrderStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model 
{
    use HasFactory, SoftDeletes, GetStats;

    protected $casts = [
        'subtotal'  => MoneyCast::class,
        'tax'       => MoneyCast::class,
        'total'     => MoneyCast::class,
        'status'    => OrderStatusEnum::class,
    ];

    public function inventory(): HasOne
    {
        return $this->hasOne(VehicleInventory::class);
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function specialist(): BelongsTo
    {
        return $this->belongsTo(Specialist::class);
    }

    public function station(): BelongsTo
    {
        return $this->belongsTo(Station::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
