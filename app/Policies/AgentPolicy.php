<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Agent;
use App\Models\User;

class AgentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('listar agentes');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Agent $agent): bool
    {
        return $user->checkPermissionTo('ver agentes');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('crear agentes');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Agent $agent): bool
    {
        return $user->checkPermissionTo('actualizar agentes');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Agent $agent): bool
    {
        return $user->checkPermissionTo('eliminar agentes');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Agent $agent): bool
    {
        return $user->checkPermissionTo('recuperar agentes');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Agent $agent): bool
    {
        return $user->checkPermissionTo('forzar-eliminacion agentes');
    }
}
