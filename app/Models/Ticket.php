<?php

namespace App\Models;

use Coolsam\NestedComments\Concerns\HasComments;
use Coolsam\NestedComments\Concerns\HasReactions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;

class Ticket extends Model
{
    use SoftDeletes, HasComments, HasReactions;

    protected $fillable = [
        'support_topic_id',
        'created_by',
        'title',
        'description',
        'status_id',
        'priority',
        'created_from_ip_address',
        'branch_id',
    ];

    public function supportTopic()
    {
        return $this->belongsTo(SupportTopic::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function creator()
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

    public function status()
    {
        return $this->belongsTo(TicketStatus::class);
    }

    public function scopeUnassigned($query)
    {
        return $query->whereDoesntHave('agents');
    }

    public function scopeAssigned($query)
    {
        return $query->whereHas('agents');
    }

    public function assignments()
    {
        return $this->hasMany(TicketAgentAssignment::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (auth()->check()) {
                $currentUser = auth()->user();
                $model->created_by = $currentUser->id;
                $model->created_from_ip_address = request()->ip();
                $model->branch_id = $currentUser->branch->id ?? null;
            }
        });
    }

    public function scopeForSupportUnitUser($query, $userId = null)
    {
        $userId = $userId ?? auth()->id();

        return $query->whereHas('supportTopic.supportUnit.agents', function ($q) use ($userId) {
            $q->where('users.id', $userId);
        });
    }

    public function scopeMyTickets($query, $userId = null)
    {
        $userId = $userId ?? auth()->id();

        return $query->whereHas('agents', function ($q) use ($userId) {
            $q->where('users.id', $userId);
        });
    }
}
