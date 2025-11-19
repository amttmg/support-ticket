<x-mail::message>
    # Ticket Resolved ✅

    Hi {{ $ticket->creator->name }},

    Your ticket **{{ $ticket->ticket_id }} - {{ $ticket->title }}** has been resolved.

    Please verify if the issue is fixed:

    - If fixed, you can **close the ticket**
    - If not fixed, you can **re-open the ticket**
    - If no action is taken, this ticket will automatically **close after 24 hours**



    Thanks,
    {{ config('app.name') }}
</x-mail::message>
