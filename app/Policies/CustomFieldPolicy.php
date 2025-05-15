<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\CustomField;
use App\Models\User;

class CustomFieldPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any CustomField');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CustomField $customfield): bool
    {
        return $user->checkPermissionTo('view CustomField');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create CustomField');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CustomField $customfield): bool
    {
        return $user->checkPermissionTo('update CustomField');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CustomField $customfield): bool
    {
        return $user->checkPermissionTo('delete CustomField');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any CustomField');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CustomField $customfield): bool
    {
        return $user->checkPermissionTo('restore CustomField');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any CustomField');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, CustomField $customfield): bool
    {
        return $user->checkPermissionTo('replicate CustomField');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder CustomField');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CustomField $customfield): bool
    {
        return $user->checkPermissionTo('force-delete CustomField');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any CustomField');
    }
}
