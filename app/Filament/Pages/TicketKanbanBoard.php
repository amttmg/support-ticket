<?php

namespace App\Filament\Pages;

use App\Models\Ticket;
use App\Models\TicketStatus;
use Mokhosh\FilamentKanban\Pages\KanbanBoard;
use Illuminate\Support\Collection;

class TicketKanbanBoard extends KanbanBoard
{
    protected static string $model = Ticket::class;
    protected static string $statusEnum = TicketStatus::class;
    protected static string $recordStatusAttribute = 'status_id';
}
