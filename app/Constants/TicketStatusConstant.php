<?php

namespace App\Constants;

class TicketStatusConstant
{

    const OPEN = 1;
    const IN_PROGRESS = 2;
    const RESOLVED = 3;
    const CLOSED = 4;

    public static function getStatuses()
    {
        return [
            ['id' => self::OPEN, 'name' => 'Open', 'slug' => 'open'],
            ['id' => self::IN_PROGRESS, 'name' => 'In Progress', 'slug' => 'in-progress'],
            ['id' => self::RESOLVED, 'name' => 'Resolved', 'slug' => 'resolved'],
            ['id' => self::CLOSED, 'name' => 'Closed', 'slug' => 'closed'],
        ];
    }
}
