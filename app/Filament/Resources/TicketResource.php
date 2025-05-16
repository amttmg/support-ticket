<?php

namespace App\Filament\Resources;

use App\Constants\PermissionConstants;
use App\Filament\Helpers\TicketForms;
use App\Filament\Resources\TicketResource\Pages;
use App\Filament\Resources\TicketResource\RelationManagers;
use App\Models\Ticket;
use App\Models\TicketAgentAssignment;
use App\Models\User;
use Dom\Text;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Unassign Tickets';

    protected static ?string $pluralLabel = 'Unassigned Tickets';

    protected static ?string $navigationGroup = 'Support';

    protected static ?string $breadcrumb = 'Tickets';

    public static function form(Form $form): Form
    {
        return $form->schema(TicketForms::basicSchema(true));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Ticket::query()->forSupportUnitUser()->unassigned()->with(['supportTopic', 'status', 'creator']))
            ->columns([

                Tables\Columns\TextColumn::make('title')
                    ->searchable(),

                Tables\Columns\TextColumn::make('supportTopic.name')
                    ->sortable(),

                Tables\Columns\TextColumn::make('priority')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'low' => 'gray',
                        'medium' => 'info',
                        'high' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Created By')
                    ->placeholder('Unknown'),

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
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

                Tables\Actions\Action::make('assignUsers')
                    ->visible(fn() => auth()->user()->can(PermissionConstants::PERMISSION_ASSIGN_TO_OTHERS))
                    ->label('Assign')
                    ->icon('heroicon-o-user-plus')
                    ->modalHeading('Assign Users to Ticket')
                    ->modalWidth('sm')
                    ->form([
                        Select::make('user_ids')
                            ->label('Assign to Users')
                            ->options(User::query()->pluck('name', 'id'))
                            ->multiple()
                            ->searchable()
                            ->required(),
                    ])
                    ->action(function (Ticket $record, array $data): void {
                        $existingAssignments = $record->assignments()->pluck('user_id')->toArray();
                        $newAssignments = array_diff($data['user_ids'], $existingAssignments);

                        foreach ($newAssignments as $userId) {
                            TicketAgentAssignment::create([
                                'ticket_id' => $record->id,
                                'user_id' => $userId,
                                'role' => 'agent',
                                'status' => 'assigned'
                            ]);
                        }

                        // Update ticket status if not already assigned
                        if ($record->assignments()->count() > 0 && $record->status->slug !== 'assigned') {
                            $assignedStatus = \App\Models\TicketStatus::where('slug', 'assigned')->first();
                            if ($assignedStatus) {
                                $record->update(['status_id' => $assignedStatus->id]);
                            }
                        }

                        Notification::make()
                            ->title(count($newAssignments) . ' users assigned successfully')
                            ->success()
                            ->send();
                    }),
                Tables\Actions\Action::make('assignSelf')
                    ->visible(fn() => auth()->user()->can(PermissionConstants::PERMISSION_ASSIGN_TO_ME))
                    ->label('Claim')
                    ->icon('heroicon-o-user-circle')
                    ->requiresConfirmation()
                    ->modalHeading('Claim Ticket')
                    ->modalDescription('Are you sure you want to assign yourself on this ticket?')
                    ->modalWidth('sm')
                    ->action(function (Ticket $record): void {
                        TicketAgentAssignment::create([
                            'ticket_id' => $record->id,
                            'user_id' => auth()->user()->id,
                            'role' => 'agent',
                            'status' => 'assigned'
                        ]);
                        Notification::make()
                            ->title('users assigned successfully')
                            ->success()
                            ->send();
                    }),

            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            // 'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }
}
