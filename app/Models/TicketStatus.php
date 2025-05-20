<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketStatus extends Model
{
    public static function statuses()
    {

        return TicketStatus::all()->map(function ($status) {
            return [
                'id' => $status->id,
                'title' => $status->name,
                'slug' => $status->slug,
                'color' => $status->color,
                'is_default' => $status->is_default,
                'order' => $status->order,
            ];
        });
    }
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'status_id');
    }
}
