<?php

namespace App\Policies;

use App\Models\User;

class PermissionPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    // PermissionPolicy.php
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin();
    }
}
