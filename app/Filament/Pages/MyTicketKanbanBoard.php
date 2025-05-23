<?php

namespace App\Filament\Pages;

use App\Constants\GeneralSetup;
use App\Constants\TicketStatusConstant;
use App\Filament\Helpers\TicketForms;
use App\Filament\Resources\TicketResource;
use App\Models\Ticket;
use App\Models\TicketStatus;
use Filament\Forms\Form;
use Mokhosh\FilamentKanban\Pages\KanbanBoard;
use Illuminate\Support\Collection;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class MyTicketKanbanBoard extends KanbanBoard
{
    protected static string $model = Ticket::class;
    protected static string $statusEnum = TicketStatus::class;
    protected static string $recordStatusAttribute = 'status_id';

    protected static ?string $navigationLabel = 'My Tickets';

    protected static ?string $pluralLabel = 'My Tickets';

    protected static ?string $navigationGroup = 'Support';

    protected static ?string $breadcrumb = 'Tickets';

    protected static ?string $title = 'My Tickets';

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';



    protected function records(): Collection
    {
        return Ticket::query()
            ->with(['status'])
            ->whereHas('agents', function ($query) {
                $query->where('users.id', auth()->id());
            })
            ->get();
    }

    public function infolist(Infolist $infolist): Infolist
    {
        $ticket = Ticket::find($this->editModalRecordId);

        if (! $ticket) {
            return $infolist
                ->make()
                ->state([
                    'title' => 'Not Found',
                    'description' => 'The requested ticket could not be found.',
                ])
                ->schema([
                    Infolists\Components\TextEntry::make('title')
                        ->label('Error')
                        ->color('danger'),
                    Infolists\Components\TextEntry::make('description'),
                ]);
        }

        return $infolist
            ->make()
            ->record($ticket)
            ->schema(TicketForms::basicInfoListSchema());
    }
    protected function getEditModalWidth(): string
    {
        return '5xl'; // or '7xl' for wider
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) Ticket::query()
            ->myTickets()
            ->where('status_id', TicketStatusConstant::OPEN)
            ->count();
    }
    public static function getNavigationBadgeColor(): ?string
    {
        return GeneralSetup::NAVIGATION_COUNT_COLOR;
    }
}
