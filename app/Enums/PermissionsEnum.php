<?php 

namespace App\Enums;

use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasLabel;

enum PermissionsEnum: string implements HasLabel, HasDescription
{
    // Estimates
    case VIEWANYESTIMATES   = 'view-any estimates';
    case VIEWESTIMATES      = 'view estimates';
    case CREATEESTIMATES    = 'create estimates';
    case UPDATEESTIMATES    = 'update estimates';
    case DELETEESTIMATES    = 'delete estimates';
    // Permisos de ordenes
    case VIEWANYORDERS      = 'view-any orders';
    case VIEWORDER          = 'view order';
    case CREATEORDER        = 'create order';
    case UPDATEORDER        = 'update order';
    case DELETEORDER        = 'delete order';
    // Clientes 
    case VIEWANYCLIENTS     = 'view-any clients';
    case VIEWCLIENT         = 'view client';
    case CREATECLIENT       = 'create client';
    case UPDATECLIENT       = 'update client';
    case DELETECLIENT       = 'delete client';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::VIEWANYESTIMATES  => 'Listar cotizaciones',
            self::VIEWESTIMATES     => 'Ver cotización',
            self::CREATEESTIMATES   => 'Crear cotización',
            self::UPDATEESTIMATES   => 'Actualizar cotización',
            self::DELETEESTIMATES   => 'Eliminar cotización',

            self::VIEWANYORDERS     => 'Listar órdenes',
            self::VIEWORDER         => 'Ver orden',
            self::CREATEORDER       => 'Crear orden',
            self::UPDATEORDER       => 'Actualizar orden',
            self::DELETEORDER       => 'Eliminar orden',

            self::VIEWANYCLIENTS    => 'Ver cualquier cliente',
            self::VIEWCLIENT        => 'Ver cliente',
            self::CREATECLIENT      => 'Crear cliente',
            self::UPDATECLIENT      => 'Actualizar cliente',
            self::DELETECLIENT      => 'Eliminar cliente',

            
        };
    }

    public function getDescription(): ?string
    {
        return match ($this) {
            self::VIEWANYESTIMATES  => 'Permite ver la lista de cotizaciones.',
            self::VIEWESTIMATES     => 'Permite ver una sola cotizacion.',
            self::CREATEESTIMATES   => 'Permite crear cotizaciones.',
            self::UPDATEESTIMATES   => 'Permite actualizar cotizaciones.',
            self::DELETEESTIMATES   => 'Permite eliminar cotizaciones.',

            self::VIEWANYORDERS     => 'Permite ver cualquier pedido en el sistema',
            self::VIEWORDER         => 'Permite ver un pedido específico',
            self::CREATEORDER       => 'Permite crear un nuevo pedido',
            self::UPDATEORDER       => 'Permite actualizar un pedido existente',
            self::DELETEORDER       => 'Permite eliminar un pedido existente',

            self::VIEWANYCLIENTS    => 'Permite ver cualquier cliente en el sistema',
            self::VIEWCLIENT        => 'Permite ver un cliente específico',
            self::CREATECLIENT      => 'Permite crear un nuevo cliente',
            self::UPDATECLIENT      => 'Permite actualizar un cliente existente',
            self::DELETECLIENT      => 'Permite eliminar un cliente existente',
        };
    }

    public static function all(): array
    {
        return [
            self::VIEWANYESTIMATES,
            self::VIEWESTIMATES,
            self::CREATEESTIMATES,
            self::UPDATEESTIMATES,
            self::DELETEESTIMATES,

            self::VIEWANYORDERS,
            self::VIEWORDER,
            self::CREATEORDER,
            self::UPDATEORDER,
            self::DELETEORDER,

            self::VIEWANYCLIENTS,
            self::VIEWCLIENT,
            self::CREATECLIENT,
            self::UPDATECLIENT,
            self::DELETECLIENT,
        ];
    }
}