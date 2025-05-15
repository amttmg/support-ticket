<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\TicketInternalNote;
use App\Models\User;

class TicketInternalNotePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any TicketInternalNote');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TicketInternalNote $ticketinternalnote): bool
    {
        return $user->checkPermissionTo('view TicketInternalNote');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create TicketInternalNote');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TicketInternalNote $ticketinternalnote): bool
    {
        return $user->checkPermissionTo('update TicketInternalNote');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TicketInternalNote $ticketinternalnote): bool
    {
        return $user->checkPermissionTo('delete TicketInternalNote');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any TicketInternalNote');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TicketInternalNote $ticketinternalnote): bool
    {
        return $user->checkPermissionTo('restore TicketInternalNote');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any TicketInternalNote');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, TicketInternalNote $ticketinternalnote): bool
    {
        return $user->checkPermissionTo('replicate TicketInternalNote');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder TicketInternalNote');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TicketInternalNote $ticketinternalnote): bool
    {
        return $user->checkPermissionTo('force-delete TicketInternalNote');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any TicketInternalNote');
    }
}
