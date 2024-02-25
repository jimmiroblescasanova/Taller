<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum EstimateStatusEnum: string implements HasLabel, HasColor
{
    case AVAILABLE  = 'available';
    case ORDERED    = 'ordered';
    case CANCELLED  = 'cancelled';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::AVAILABLE     => 'Disponible',
            self::ORDERED       => 'Completada',
            self::CANCELLED     => 'Cancelada',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::AVAILABLE     => 'success',
            self::ORDERED       => 'gray',
            self::CANCELLED     => 'danger',
        };
    }
}