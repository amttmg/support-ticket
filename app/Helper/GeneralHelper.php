<?php

use App\Models\Branch;

function getBranchIdFromIp($ip)
{
    $networkPart = implode('.', array_slice(explode('.', $ip), 0, 3));
    return Branch::where('ip_range', 'like', $networkPart . '.%')->first();
}

function getStatusColor($status)
{
    return match ($status) {
        'Open' => 'primary',
        'In Progress' => 'success',
        'Resolved' => 'warning',
        'Closed' => 'danger',
        'Rejected' => 'danger',
        default => 'secondary',
    };
}
