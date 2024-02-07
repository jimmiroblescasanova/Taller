<?php 

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum ProductType: string implements HasLabel, HasColor
{
    case PRODUCT    = '1';
    case SERVICE    = '3';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PRODUCT   => 'Producto',
            self::SERVICE   => 'Servicio',
        };
    }

    public function getColor(): ?string
    {
        return match ($this) {
            self::PRODUCT   => 'info',
            self::SERVICE   => 'warning',
        };
    }
}