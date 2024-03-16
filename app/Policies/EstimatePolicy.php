<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Estimate;
use App\Models\User;

class EstimatePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('listar cotizaciones');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Estimate $estimate): bool
    {
        return $user->checkPermissionTo('ver cotizaciones');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('crear cotizaciones');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Estimate $estimate): bool
    {
        return $user->checkPermissionTo('actualizar cotizaciones');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Estimate $estimate): bool
    {
        return $user->checkPermissionTo('eliminar cotizaciones');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Estimate $estimate): bool
    {
        return $user->checkPermissionTo('recuperar cotizaciones');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Estimate $estimate): bool
    {
        return $user->checkPermissionTo('forzar-eliminacion cotizaciones');
    }
}
