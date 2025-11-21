<?php

namespace App\Observers;

use App\Models\Ticket;
use App\Mail\TicketResolved;
use App\Constants\TicketStatusConstant;
use App\Notifications\SlackNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class TicketObserver
{
    public function creating(Ticket $ticket)
    {
        if (auth()->check()) {
            $user = auth()->user();

            $ticket->created_by = $user->id;
            $ticket->created_from_ip_address = request()->ip();
            $ticket->branch_id = $user->branch->id ?? null;
        }
    }

    public function created(Ticket $ticket)
    {
        if (!config('app.enable_slack')) {
            return;
        }

        $slackUrl = $ticket->supportTopic->supportUnit->slack_url;
        $ticketUrl = route('filament.app.resources.tickets.index');

        $message = "<!channel> New Ticket {$ticket->ticket_id} ({$ticket->supportTopic->name}) - "
            . "`{$ticket->title}` created by {$ticket->creator->name} "
            . "({$ticket->creator->branch->name} - {$ticket->creator->branch->code}) "
            . "| <{$ticketUrl}|View Ticket>";

        Notification::route('slack', $slackUrl)
            ->notify(new SlackNotification($message, $slackUrl));
    }

    public function updated(Ticket $ticket)
    {
        $resolvedStatus = TicketStatusConstant::RESOLVED;

        if ($ticket->isDirty('status_id') && $ticket->status_id == $resolvedStatus) {
            if ($ticket->creator && $ticket->creator->email) {
                Mail::to($ticket->creator->email)
                    ->send(new TicketResolved($ticket));
            }
        }
    }
}
