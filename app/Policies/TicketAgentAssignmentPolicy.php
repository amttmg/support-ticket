<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\TicketAgentAssignment;
use App\Models\User;

class TicketAgentAssignmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any TicketAgentAssignment');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TicketAgentAssignment $ticketagentassignment): bool
    {
        return $user->checkPermissionTo('view TicketAgentAssignment');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create TicketAgentAssignment');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TicketAgentAssignment $ticketagentassignment): bool
    {
        return $user->checkPermissionTo('update TicketAgentAssignment');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TicketAgentAssignment $ticketagentassignment): bool
    {
        return $user->checkPermissionTo('delete TicketAgentAssignment');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any TicketAgentAssignment');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TicketAgentAssignment $ticketagentassignment): bool
    {
        return $user->checkPermissionTo('restore TicketAgentAssignment');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any TicketAgentAssignment');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, TicketAgentAssignment $ticketagentassignment): bool
    {
        return $user->checkPermissionTo('replicate TicketAgentAssignment');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder TicketAgentAssignment');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TicketAgentAssignment $ticketagentassignment): bool
    {
        return $user->checkPermissionTo('force-delete TicketAgentAssignment');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any TicketAgentAssignment');
    }
}
