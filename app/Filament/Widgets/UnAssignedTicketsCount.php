<?php

namespace App\Filament\Widgets;

use App\Constants\TicketStatusConstant;
use App\Models\Ticket;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UnAssignedTicketsCount extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Unassigned Tickets', Ticket::query()->forSupportUnitUser()->unassigned()->count())
                ->icon('heroicon-o-inbox')
                ->color('danger')
                //->description('Tickets waiting for assignment')
                // ->descriptionIcon('heroicon-o-clock')
                // ->chart($this->getTicketTrend('unassigned'))
                ->extraAttributes(['class' => 'hover:shadow-lg transition-shadow']),

            Stat::make('Open Tickets', Ticket::query()
                ->forSupportUnitUser()
                ->where('status_id', TicketStatusConstant::OPEN)
                ->count())
                ->icon('heroicon-o-exclamation-circle')
                ->color('warning')
                //->description('Active tickets needing attention')
                //->descriptionIcon('heroicon-o-eye')
                // ->chart($this->getTicketTrend('open'))
                ->extraAttributes(['class' => 'hover:shadow-lg transition-shadow']),

            Stat::make("In Progress Tickets", Ticket::query()
                ->forSupportUnitUser()
                ->where('status_id', TicketStatusConstant::IN_PROGRESS)
                ->count())
                ->icon('heroicon-o-arrow-path')
                ->color('primary')
                // ->description('Tickets being worked on')
                // ->descriptionIcon('heroicon-o-cog')
                // ->chart($this->getTicketTrend('in_progress'))
                ->extraAttributes(['class' => 'hover:shadow-lg transition-shadow']),

            Stat::make("Today's Resolved Tickets", Ticket::query()
                ->forSupportUnitUser()
                ->where('status_id', TicketStatusConstant::RESOLVED)
                ->count())
                ->icon('heroicon-o-check-circle')
                ->color('success')
                // ->description('Recently resolved tickets')
                // ->descriptionIcon('heroicon-o-flag')
                // ->chart($this->getTicketTrend('resolved'))
                ->extraAttributes(['class' => 'hover:shadow-lg transition-shadow']),

            Stat::make("Total Closed Tickets", Ticket::query()
                ->forSupportUnitUser()
                ->where('status_id', TicketStatusConstant::CLOSED)
                ->count())
                ->icon('heroicon-o-archive-box')
                ->color('gray')
                // ->description('Completed and closed tickets')
                // ->descriptionIcon('heroicon-o-lock-closed')
                // ->chart($this->getTicketTrend('closed'))
                ->extraAttributes(['class' => 'hover:shadow-lg transition-shadow']),
        ];
    }

    // Helper method to generate trend data (optional)
    protected function getTicketTrend(string $type): array
    {
        // Implement your logic to get trend data for each ticket type
        // This is just a placeholder example
        return match ($type) {
            'unassigned' => [1, 1, 1, 1, 1, 1, 1],
            'open' => [5, 6, 7, 8, 7, 9, 10],
            'in_progress' => [3, 4, 5, 4, 6, 5, 7],
            'resolved' => [8, 7, 9, 10, 12, 11, 13],
            'closed' => [10, 12, 11, 13, 15, 14, 16],
            default => [],
        };
    }
}
