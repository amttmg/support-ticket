<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\TicketCustomFieldValue;
use App\Models\User;

class TicketCustomFieldValuePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any TicketCustomFieldValue');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TicketCustomFieldValue $ticketcustomfieldvalue): bool
    {
        return $user->checkPermissionTo('view TicketCustomFieldValue');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create TicketCustomFieldValue');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TicketCustomFieldValue $ticketcustomfieldvalue): bool
    {
        return $user->checkPermissionTo('update TicketCustomFieldValue');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TicketCustomFieldValue $ticketcustomfieldvalue): bool
    {
        return $user->checkPermissionTo('delete TicketCustomFieldValue');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any TicketCustomFieldValue');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TicketCustomFieldValue $ticketcustomfieldvalue): bool
    {
        return $user->checkPermissionTo('restore TicketCustomFieldValue');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any TicketCustomFieldValue');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, TicketCustomFieldValue $ticketcustomfieldvalue): bool
    {
        return $user->checkPermissionTo('replicate TicketCustomFieldValue');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder TicketCustomFieldValue');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TicketCustomFieldValue $ticketcustomfieldvalue): bool
    {
        return $user->checkPermissionTo('force-delete TicketCustomFieldValue');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any TicketCustomFieldValue');
    }
}
