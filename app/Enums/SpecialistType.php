<?php 

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum SpecialistType: string implements HasLabel
{
    case MANAGER    = 'gerente';
    case MECHANIC    = 'mecanico';
    case TECHNICIAN    = 'tecnico';
    case ELECTRICIAN    = 'electrico';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::MANAGER    => 'Gerente de Taller',
            self::MECHANIC    => 'Mecánico',
            self::TECHNICIAN    => 'Técnico',
            self::ELECTRICIAN    => 'Eléctrico',
        };
    }
}