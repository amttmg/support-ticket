<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\SupportUnit;
use App\Models\User;

class SupportUnitPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any SupportUnit');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SupportUnit $supportunit): bool
    {
        return $user->checkPermissionTo('view SupportUnit');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create SupportUnit');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SupportUnit $supportunit): bool
    {
        return $user->checkPermissionTo('update SupportUnit');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SupportUnit $supportunit): bool
    {
        return $user->checkPermissionTo('delete SupportUnit');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any SupportUnit');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SupportUnit $supportunit): bool
    {
        return $user->checkPermissionTo('restore SupportUnit');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any SupportUnit');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, SupportUnit $supportunit): bool
    {
        return $user->checkPermissionTo('replicate SupportUnit');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder SupportUnit');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SupportUnit $supportunit): bool
    {
        return $user->checkPermissionTo('force-delete SupportUnit');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any SupportUnit');
    }
}
