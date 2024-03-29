<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Agent extends Model
{
    use HasFactory;

    protected $casts = [
        'active' => Status::class,
    ];

    public function scopeIsActive(Builder $query): Builder 
    {
        return $query->where('active', true);
    }

    public function name(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => ucwords($value),
        );
    }
}
