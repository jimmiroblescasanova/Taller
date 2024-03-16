<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\VehicleInventory;
use App\Models\User;

class VehicleInventoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('listar inventarios');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, VehicleInventory $vehicleinventory): bool
    {
        return $user->checkPermissionTo('ver inventarios');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('crear inventarios');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, VehicleInventory $vehicleinventory): bool
    {
        return $user->checkPermissionTo('actualizar inventarios');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, VehicleInventory $vehicleinventory): bool
    {
        return $user->checkPermissionTo('eliminar inventarios');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, VehicleInventory $vehicleinventory): bool
    {
        return $user->checkPermissionTo('recuperar inventarios');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, VehicleInventory $vehicleinventory): bool
    {
        return $user->checkPermissionTo('forzar-eliminacion inventarios');
    }
}
