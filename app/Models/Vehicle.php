<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehicle extends Model
{
    use HasFactory;

    protected function licensePlate(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => strtoupper($value),
        );
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function vehicle_type(): BelongsTo
    {
        return $this->belongsTo(VehicleType::class);
    }

    public function vehicle_brand(): BelongsTo
    {
        return $this->belongsTo(VehicleBrand::class);
    }
}
