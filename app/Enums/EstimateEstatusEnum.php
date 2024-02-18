<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum EstimateEstatusEnum: int implements HasLabel, HasColor
{
    case AVAILABLE  = 0;
    case ORDERED    = 1;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::AVAILABLE => 'Disponible',
            self::ORDERED   => 'Completada',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::AVAILABLE => 'success',
            self::ORDERED   => 'gray',
        };
    }
}