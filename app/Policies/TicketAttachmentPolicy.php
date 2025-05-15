<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\TicketAttachment;
use App\Models\User;

class TicketAttachmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any TicketAttachment');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TicketAttachment $ticketattachment): bool
    {
        return $user->checkPermissionTo('view TicketAttachment');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create TicketAttachment');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TicketAttachment $ticketattachment): bool
    {
        return $user->checkPermissionTo('update TicketAttachment');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TicketAttachment $ticketattachment): bool
    {
        return $user->checkPermissionTo('delete TicketAttachment');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any TicketAttachment');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TicketAttachment $ticketattachment): bool
    {
        return $user->checkPermissionTo('restore TicketAttachment');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any TicketAttachment');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, TicketAttachment $ticketattachment): bool
    {
        return $user->checkPermissionTo('replicate TicketAttachment');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder TicketAttachment');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TicketAttachment $ticketattachment): bool
    {
        return $user->checkPermissionTo('force-delete TicketAttachment');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any TicketAttachment');
    }
}
