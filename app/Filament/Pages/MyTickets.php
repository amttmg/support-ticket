<?php

namespace App\Filament\Pages;

use App\Constants\PermissionConstants;
use App\Filament\Helpers\TicketForms;
use App\Models\Ticket; // Make sure to use your Ticket model
use App\Models\TicketAgentAssignment;
use App\Models\User;
use App\Traits\HasStatusChange;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction as ActionsViewAction;

class MyTickets extends Page implements HasTable
{
    use InteractsWithTable, HasStatusChange;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static string $view = 'filament.pages.all-tickets';

    protected static ?string $navigationLabel = 'My Tickets';

    protected static ?string $navigationGroup = 'Reports';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return (string) self::getQuery()
            ->count();
    }
    public static function getQuery()
    {
        return Ticket::query()->myTickets()->latest()->with(['supportTopic', 'status', 'creator']);
    }
    public function table(Table $table): Table
    {
        return $table
            ->query($this->getQuery()) // Use your Ticket model
            ->columns([

                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->formatStateUsing(fn($state) => '#' . $state),

                Tables\Columns\TextColumn::make('title')
                    ->limitWithTooltip(10)
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
                    ->color(fn(string $state): string => getStatusColor($state)),

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
                    ->label('Support Topic')
                    ->relationship('supportTopic', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('branch_id')
                    ->relationship('branch', 'name')
                    ->label('Branch')
                    ->searchable()
                    ->getOptionLabelFromRecordUsing(fn($record) => "{$record->code}-{$record->name}")
                    ->preload(),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('Created From'),
                        DatePicker::make('created_until')
                            ->label('Created Until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['created_from'], fn($q, $date) => $q->whereDate('created_at', '>=', $date))
                            ->when($data['created_until'], fn($q, $date) => $q->whereDate('created_at', '<=', $date));
                    }),
            ])
            ->actions([
                ActionsViewAction::make()
                    ->infolist(TicketForms::basicInfoListSchema()),

                Tables\Actions\Action::make('assignUsers')
                    ->visible(fn() => auth()->user()->can(PermissionConstants::PERMISSION_FORWARD_TICKET))
                    ->label('Forward')
                    ->icon('heroicon-o-arrow-right')
                    ->modalHeading('Assign Users to Ticket')
                    ->modalWidth('sm')
                    ->form([
                        Select::make('user_ids')
                            ->label('Assign to Users')
                            ->options(self::getUsersToAssign())
                            //->multiple()
                            ->searchable()
                            ->required(),
                    ])
                    ->action(function (Ticket $record, $data): void {
                        if (!is_array($data['user_ids'])) {
                            $data['user_ids'] = [$data['user_ids']];
                        }

                        // Get existing assignments
                        $existingAssignments = $record->assignments()->pluck('user_id')->toArray();

                        // Remove assignments that are no longer in the new list
                        $toRemove = array_diff($existingAssignments, $data['user_ids']);
                        TicketAgentAssignment::where('ticket_id', $record->id)
                            ->whereIn('user_id', $toRemove)
                            ->delete();

                        // Add or keep assignments in the new list
                        foreach ($data['user_ids'] as $userId) {
                            TicketAgentAssignment::updateOrCreate(
                                [
                                    'ticket_id' => $record->id,
                                    'user_id' => $userId
                                ],
                                [
                                    'role' => 'agent',
                                    'status' => 'assigned'
                                ]
                            );
                        }

                        // Notifications for newly assigned users
                        $newAssignments = $data['user_ids'];
                        Notification::make()
                            ->title('You have been assigned a new ticket #' . $record->id . ': ' . $record->title . ' by ' . auth()->user()->name)
                            ->success()
                            ->sendToDatabase(User::whereIn('id', $newAssignments)->get());

                        // Notifications for ticket creator
                        if ($record->creator) {
                            $assignedUsers = User::whereIn('id', $newAssignments)->pluck('name')->toArray();
                            Notification::make()
                                ->title('Your ticket #' . $record->id . ' has been assigned to: ' . implode(', ', $assignedUsers))
                                ->success()
                                ->sendToDatabase($record->creator);
                        }

                        // Success notification
                        Notification::make()
                            ->title(count($newAssignments) . ' users assigned successfully')
                            ->success()
                            ->send();
                    }),

            ])
            ->bulkActions([
                // Add bulk actions if needed
            ]);
    }
    public static function getUsersToAssign(): array
    {
        $supportUnitIds = auth()->user()->supportUnits()->pluck('support_units.id');
        return User::query()
            ->whereHas('supportUnits', function ($q) use ($supportUnitIds) {
                $q->whereIn('support_units.id', $supportUnitIds);
            })
            // ->where('id', '!=', auth()->id())
            ->where('user_type', 'back')
            ->pluck('name', 'id')
            ->toArray(); // Exclude current user
    }
}
