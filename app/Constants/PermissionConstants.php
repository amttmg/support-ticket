<?php

namespace App\Constants;

class PermissionConstants
{

    const PERMISSION_ASSIGN_TO_OTHERS = 'Assign-to-other Ticket';
    const PERMISSION_ASSIGN_TO_ME = 'Assign-to-me Ticket';
    const PERMISSION_ALL_TICKET = 'Show-all-kanban-board Ticket';

    const PERMISSION_BRANCH_MANAGER = 'Branch Manager';


    // const PERMISSION_READ = 'read';
    // const PERMISSION_UPDATE = 'update';
    // const PERMISSION_DELETE = 'delete';

    const PERMISSION_ALL_ADMIN = [
        self::PERMISSION_ASSIGN_TO_OTHERS,
        self::PERMISSION_ASSIGN_TO_ME,
        self::PERMISSION_ALL_TICKET,
    ];

    const PERMISSION_BRANCH_FRONT = [
        self::PERMISSION_BRANCH_MANAGER,
    ];
}
