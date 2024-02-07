<?php 

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ContactMedia: string implements HasLabel
{
    case SOCIAL     = 'social';
    case CONTACT    = 'contact';
    case PUBLICITY  = 'publicity';
    case LOCATION   = 'location';
    case COMPANY    = 'company';
    case OTHER      = 'other';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::SOCIAL     => 'Redes sociales',
            self::CONTACT    => 'Recomendación de conocidos',
            self::PUBLICITY  => 'Publicidad',
            self::LOCATION   => 'Ubicación',
            self::COMPANY    => 'Por trabajo',
            self::OTHER      => 'Otro',
        };
    }
}