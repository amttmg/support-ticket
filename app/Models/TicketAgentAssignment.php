<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketAgentAssignment extends Pivot
{
    use SoftDeletes;

    protected $table = 'ticket_agent_assignments'; // ✅ fix


    protected $fillable = ['ticket_id', 'user_id', 'role', 'status'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $currentUser = auth()->user();
            if ($model->user_id == $currentUser->id) {
                $description = 'Ticket is  claimed by ' . $model->user->name;
            } else {
                $description = 'Ticket is  assigned to ' . $model->user->name;
            }

            activity()
                ->useLog('Ticket Assignment')
                ->performedOn($model->ticket)
                ->causedBy($currentUser ?? null) // optional, if user is logged in
                ->withProperties(['attributes' => $model->getAttributes()])
                ->tap(function ($activity) {
                    $activity->event = 'assigned'; // custom event name
                })
                ->log($description);
        });
    }
}
