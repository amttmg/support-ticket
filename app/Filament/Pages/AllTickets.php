<?php

namespace App\Filament\Pages;

use App\Filament\Helpers\TicketForms;
use App\Models\Ticket; // Make sure to use your Ticket model
use Filament\Actions\ViewAction;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction as ActionsViewAction;

class AllTickets extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.all-tickets';

    protected static ?string $navigationLabel = 'All Tickets';

    protected static ?string $navigationGroup = 'Reports';

    protected static ?int $navigationSort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(Ticket::query()->forSupportUnitUser()->with(['supportTopic', 'status', 'creator'])) // Use your Ticket model
            ->columns([

                Tables\Columns\TextColumn::make('title')
                    ->searchable(),

                Tables\Columns\TextColumn::make('supportTopic.name')
                    ->sortable(),

                Tables\Columns\TextColumn::make('priority')
                    ->sortable()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'low' => 'gray',
                        'medium' => 'info',
                        'high' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('status.name')  // Access the related status's name
                    ->label('Status')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Open' => 'primary',
                        'In Progress' => 'success',
                        'Resolved' => 'warning',
                        'Closed' => 'danger',
                        default => 'secondary',
                    }),

                Tables\Columns\TextColumn::make('branch.formatted_name')
                    ->label('Branch')
                    ->searchable('name')
                    ->placeholder('N/A'),


                Tables\Columns\TextColumn::make('created_at')
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->relationship('status', 'name'),

                Tables\Filters\SelectFilter::make('priority')
                    ->options([
                        'low' => 'Low',
                        'medium' => 'Medium',
                        'high' => 'High',
                    ]),

                Tables\Filters\SelectFilter::make('support_topic_id')
                    ->relationship('supportTopic', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('branch_id')
                    ->relationship('branch', 'name')
                    ->label('Branch')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                ActionsViewAction::make()
                    ->infolist(TicketForms::basicInfoListSchema()),
            ])
            ->bulkActions([
                // Add bulk actions if needed
            ]);
    }
}
