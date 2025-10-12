<?php

namespace App\Filament\Pages;

use App\Constants\PermissionConstants;
use App\Filament\Helpers\TicketForms;
use App\Models\Ticket; // Make sure to use your Ticket model
use App\Traits\HasStatusChange;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction as ActionsViewAction;

class AllTickets extends Page implements HasTable
{
    use InteractsWithTable, HasStatusChange;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static string $view = 'filament.pages.all-tickets';

    protected static ?string $navigationLabel = 'All Tickets';

    protected static ?string $navigationGroup = 'Reports';

    protected static ?int $navigationSort = 1;

    public static function canAccess(): bool
    {
        return auth()->user() ? auth()->user()->can(PermissionConstants::PERMISSION_ALL_TICKET) : false;
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) self::getQuery()
            ->count();
    }
    public static function getQuery()
    {
        return Ticket::query()->forSupportUnitUser()->latest()->with(['supportTopic', 'status', 'creator']);
    }
    public function table(Table $table): Table
    {
        return $table
            ->query($this->getQuery()->with(['status', 'supportTopic', 'branch'])) // Use your Ticket model
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
            ])
            ->bulkActions([
                // Add bulk actions if needed
            ]);
    }
}
