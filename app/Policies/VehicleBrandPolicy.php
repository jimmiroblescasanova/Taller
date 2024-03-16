<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\VehicleBrand;
use App\Models\User;

class VehicleBrandPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('listar marcas de vehiculos');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, VehicleBrand $vehiclebrand): bool
    {
        return $user->checkPermissionTo('ver marcas de vehiculos');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('crear marcas de vehiculos');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, VehicleBrand $vehiclebrand): bool
    {
        return $user->checkPermissionTo('actualizar marcas de vehiculos');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, VehicleBrand $vehiclebrand): bool
    {
        return $user->checkPermissionTo('eliminar marcas de vehiculos');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, VehicleBrand $vehiclebrand): bool
    {
        return $user->checkPermissionTo('recuperar marcas de vehiculos');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, VehicleBrand $vehiclebrand): bool
    {
        return $user->checkPermissionTo('forzar-eliminacion marcas de vehiculos');
    }
}
