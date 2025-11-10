<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Coolsam\NestedComments\Models\Comment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class TicketCommentController extends Controller
{
    use AuthorizesRequests;
    /**
     * Store a new comment for a ticket.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $ticketId
     * @return \Illuminate\Http\Response
     */
    public function storeComment(Request $request, $ticketId)
    {
        // Validate the request data
        $request->validate([
            'body' => 'required|string|max:1000',
        ]);

        $ticket = Ticket::findOrFail($ticketId);
        $this->authorize('view', $ticket); // Ensure the user can view the ticket
        $ticket->comments()->create([
            'body' => $request->input('body'),
            'user_id' => auth()->id(),
        ]);
    }
}
