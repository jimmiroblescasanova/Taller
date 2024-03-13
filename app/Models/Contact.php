<?php

namespace App\Models;

use App\Enums\ContactMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Contact extends Model
{
    use HasFactory;

    protected $casts = [
        'channel' => ContactMedia::class,
    ];

    public function clients(): BelongsToMany
    {
        return $this->belongsToMany(Client::class);
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    public function name(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => ucwords($value),
        );
    }

    public function lastname(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => ucwords($value),
        );
    }
}
