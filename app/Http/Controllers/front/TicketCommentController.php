<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Coolsam\NestedComments\Models\Comment;
use Illuminate\Http\Request;

class TicketCommentController extends Controller
{
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
        $ticket->comments()->create([
            'body' => $request->input('body'),
            'user_id' => auth()->id(),
        ]);
    }
}
