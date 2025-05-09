<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use SoftDeletes;

    protected $fillable = ['support_topic_id', 'created_by', 'title', 'description', 'status', 'priority'];

    public function supportTopic()
    {
        return $this->belongsTo(SupportTopic::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function agents()
    {
        return $this->belongsToMany(User::class, 'ticket_agent_assignments');
    }

    public function ticketReplies()
    {
        return $this->hasMany(TicketReply::class);
    }

    public function internalNotes()
    {
        return $this->hasMany(TicketInternalNote::class);
    }
}
