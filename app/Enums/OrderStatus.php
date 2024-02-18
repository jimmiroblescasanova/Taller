<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum OrderStatus: string implements HasLabel, HasColor
{
    case INCOMPLETE = 'incomplete';
    case PENDING    = 'pending';
    case REVIEWING  = 'reviewing';
    case PROCESING  = 'procesing';
    case COMPLETED  = 'completed';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::INCOMPLETE   => 'Incompleta',
            self::PENDING   => 'Pendiente',
            self::REVIEWING => 'En revision',
            self::PROCESING => 'En proceso',
            self::COMPLETED => 'Completada',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::INCOMPLETE    => 'danger',
            self::PENDING       => 'gray',
            self::REVIEWING     => 'primary',
            self::PROCESING     => 'info',
            self::COMPLETED     => 'success',
        };
    }
}