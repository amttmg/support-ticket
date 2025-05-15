<?php

namespace App\Constants;

class PermissionConstants
{

    const PERMISSION_ASSIGN_TO_OTHERS = 'Assign-to-other Ticket';
    const PERMISSION_ASSIGN_TO_ME = 'Assign-to-me Ticket';
    // const PERMISSION_READ = 'read';
    // const PERMISSION_UPDATE = 'update';
    // const PERMISSION_DELETE = 'delete';

    const PERMISSION_ALL = [
        self::PERMISSION_ASSIGN_TO_OTHERS,
        self::PERMISSION_ASSIGN_TO_ME,
    ];
}
