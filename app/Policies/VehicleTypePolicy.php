<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\VehicleType;
use App\Models\User;

class VehicleTypePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('listar tipos de vehiculos');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, VehicleType $vehicletype): bool
    {
        return $user->checkPermissionTo('ver tipos de vehiculos');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('crear tipos de vehiculos');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, VehicleType $vehicletype): bool
    {
        return $user->checkPermissionTo('actualizar tipos de vehiculos');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, VehicleType $vehicletype): bool
    {
        return $user->checkPermissionTo('eliminar tipos de vehiculos');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, VehicleType $vehicletype): bool
    {
        return $user->checkPermissionTo('recuperar tipos de vehiculos');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, VehicleType $vehicletype): bool
    {
        return $user->checkPermissionTo('forzar-eliminacion tipos de vehiculos');
    }
}
