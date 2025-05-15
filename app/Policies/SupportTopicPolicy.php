<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\SupportTopic;
use App\Models\User;

class SupportTopicPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any SupportTopic');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SupportTopic $supporttopic): bool
    {
        return $user->checkPermissionTo('view SupportTopic');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create SupportTopic');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SupportTopic $supporttopic): bool
    {
        return $user->checkPermissionTo('update SupportTopic');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SupportTopic $supporttopic): bool
    {
        return $user->checkPermissionTo('delete SupportTopic');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any SupportTopic');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SupportTopic $supporttopic): bool
    {
        return $user->checkPermissionTo('restore SupportTopic');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any SupportTopic');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, SupportTopic $supporttopic): bool
    {
        return $user->checkPermissionTo('replicate SupportTopic');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder SupportTopic');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SupportTopic $supporttopic): bool
    {
        return $user->checkPermissionTo('force-delete SupportTopic');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any SupportTopic');
    }
}
