<?php 

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum Status: int implements HasLabel, HasColor
{
    case ACTIVE    = 1;
    case INACTIVE  = 0;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ACTIVE   => 'Activo',
            self::INACTIVE => 'Inactivo',
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::ACTIVE   => 'success',
            self::INACTIVE => 'danger',
        };
    }
}