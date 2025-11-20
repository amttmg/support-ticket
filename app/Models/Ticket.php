<?php

namespace App\Models;

use App\Constants\TicketStatusConstant;
use App\Mail\TicketResolved;
use Illuminate\Support\Facades\Notification;
use Coolsam\NestedComments\Concerns\HasComments;
use Coolsam\NestedComments\Concerns\HasReactions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Mail;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Ticket extends Model
{
    use SoftDeletes, HasComments, HasReactions, LogsActivity;

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

    protected $attributes = [
        'status_id' => TicketStatusConstant::OPEN, // default value
    ];

    protected $appends = [
        'formatted_updated_at',
        'ticket_id',
    ];

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'title',
                'description',
                'status.name',
                'priority',
                'supportTopic.name',

            ])
            ->setDescriptionForEvent(fn(string $eventName) => "Ticket has been {$eventName}")
            ->useLogName('Ticket')
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
        // Chain fluent methods for configuration options
    }

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
        return $this->belongsToMany(User::class, 'ticket_agent_assignments')
            ->using(TicketAgentAssignment::class) // tell it to use your custom pivot
            ->withTimestamps()
            ->whereNull('ticket_agent_assignments.deleted_at'); // filter
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

    public function getFormattedUpdatedAtAttribute()
    {
        if (is_null($this->updated_at)) {
            return null;
        }

        return \Carbon\Carbon::parse($this->updated_at)->diffForHumans();
    }

    public function getTicketIdAttribute()
    {
        return '#' . $this->id;
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
        static::created(function ($ticket) {
            if (config('app.enable_slack')) {
                $slackUrl = $ticket->supportTopic->supportUnit->slack_url;
                $ticketUrl = route('filament.app.resources.tickets.index'); // or your ticket URL
                // $message = "🆕 *New Ticket Received!* <!channel> 📝\n\n"
                //     . "*Ticket ID:* {$ticket->ticket_id}\n"
                //     . "*Support Topic:* {$ticket->supportTopic->name}\n"
                //     . "*Title:* {$ticket->title}\n"
                //     . "*Created By:* {$ticket->creator->name} ({$ticket->creator->branch->name} - {$ticket->creator->branch->code})\n"
                //     . "*View Ticket:* <{$ticketUrl}|Click Here>";
                $message = $message = "<!channel> New Ticket {$ticket->ticket_id} ({$ticket->supportTopic->name}) - `{$ticket->title}` created by {$ticket->creator->name} ({$ticket->creator->branch->name} - {$ticket->creator->branch->code}) | <{$ticketUrl}|View Ticket>";
                Notification::route('slack', $slackUrl)
                    ->notify(new \App\Notifications\SlackNotification($message, $slackUrl));
            }
        });

        static::updated(function ($ticket) {
            // Check if status changed and now is 'Resolved'
            $resolvedStatusId = TicketStatusConstant::RESOLVED; // <-- replace with your actual Resolved status ID
            if ($ticket->isDirty('status_id') && $ticket->status_id == $resolvedStatusId) {
                if ($ticket->creator && $ticket->creator->email) {
                    Mail::to($ticket->creator->email)
                        ->send(new TicketResolved($ticket));
                }
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
