<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum OrderStatusEnum: string implements HasLabel, HasColor
{
    case INCOMPLETE = 'incomplete';
    case PENDING    = 'pending';
    case REVIEWING  = 'reviewing';
    case PROCESING  = 'procesing';
    case COMPLETED  = 'completed';
    case CANCELLED  = 'cancelled';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::INCOMPLETE   => 'Incompleta',
            self::PENDING   => 'Pendiente',
            self::REVIEWING => 'En revision',
            self::PROCESING => 'En proceso',
            self::COMPLETED => 'Completada',
            self::CANCELLED => 'Cancelada',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::INCOMPLETE    => 'warning',
            self::PENDING       => 'gray',
            self::REVIEWING     => 'primary',
            self::PROCESING     => 'info',
            self::COMPLETED     => 'success',
            self::CANCELLED     => 'danger',
        };
    }
}