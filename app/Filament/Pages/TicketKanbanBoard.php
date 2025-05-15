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
    protected static string $kanbanHeight = '1000px';

    protected function records(): Collection
    {
        return Ticket::query()
            ->with(['status'])
            ->whereHas('agents', function ($query) {
                $query->where('users.id', auth()->id());
            })
            ->get();
    }
}
