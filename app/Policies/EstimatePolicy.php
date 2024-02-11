<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Estimate;
use Illuminate\Auth\Access\Response;

class EstimatePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view-any estimates');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Estimate $estimate): bool
    {
        return $user->can('view estimates');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create estimates');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Estimate $estimate): bool
    {
        return $user->can('update estimates');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Estimate $estimate): bool
    {
        return $user->can('delete estimates');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Estimate $estimate): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Estimate $estimate): bool
    {
        //
    }
}
