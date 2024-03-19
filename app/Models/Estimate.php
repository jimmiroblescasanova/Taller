<?php

namespace App\Models;

use App\Casts\MoneyCast;
use App\Traits\GetStats;
use App\Enums\EstimateStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Estimate extends Model
{
    use HasFactory, SoftDeletes, GetStats;

    protected $casts = [
        'subtotal'  => MoneyCast::class,
        'tax'       => MoneyCast::class,
        'total'     => MoneyCast::class,
        'status'    => EstimateStatusEnum::class,
    ];

    public function items(): HasMany
    {
        return $this->hasMany(EstimateItem::class);
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function order(): HasOne
    {
        return $this->hasOne(Order::class);
    }
}
