<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VehicleBrand extends Model
{
    use HasFactory;

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }
}
