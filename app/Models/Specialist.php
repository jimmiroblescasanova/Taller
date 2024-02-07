<?php

namespace App\Models;

use App\Enums\Status;
use App\Enums\SpecialistType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Specialist extends Model
{
    use HasFactory;

    protected $casts = [
        'type' => SpecialistType::class,
        'active' => Status::class,
    ];
}
