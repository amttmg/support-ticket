<?php

namespace App\Filament\Pages;

use App\Models\Ticket; // Make sure to use your Ticket model
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

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
            ->query(Ticket::query()) // Use your Ticket model
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('subject')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'open' => 'success',
                        'closed' => 'danger',
                        'pending' => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('priority')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'high' => 'danger',
                        'medium' => 'warning',
                        'low' => 'success',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                // Add filters if needed
            ])
            ->actions([
                // Add actions like edit, view, etc.
            ])
            ->bulkActions([
                // Add bulk actions if needed
            ]);
    }
}
