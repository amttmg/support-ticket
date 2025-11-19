<?php
// app/Filament/Helpers/TicketForms.php
namespace App\Filament\Helpers;

use App\Constants\TicketStatusConstant;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;

use Filament\Infolists;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\Section as InfolistSection;

use Filament\Infolists\Components\Grid as InfolistGrid;
use Filament\Infolists\Components\ViewEntry;
use Filament\Support\Enums\FontWeight;
use Illuminate\Container\Attributes\Auth;

class TicketForms
{
    public static function basicSchema($disabled = false): array
    {
        //$userSupportUnitIds = auth()->user()->supportUnits->pluck('id');
        return [
            Grid::make()
                ->columns(3) // Important: define 3 columns to support 2/3 and 1/3 layout
                ->schema([
                    // Main Content (2/3 width)
                    Section::make('Ticket Details')
                        ->columnSpan(2)
                        ->schema([
                            Select::make('branch_id')
                                ->relationship('branch', 'name')
                                ->required()
                                ->disabled($disabled)
                                ->columnSpanFull()
                                ->searchable()
                                ->preload(),
                            Select::make('support_topic_id')
                                ->label('Support Topic')
                                //->searchable()
                                ->preload()
                                ->relationship(
                                    name: 'supportTopic',
                                    titleAttribute: 'name',
                                    modifyQueryUsing: function ($query) {
                                        $userUnitIds = auth()->user()->supportUnits->pluck('id');
                                        $query->whereIn('support_unit_id', $userUnitIds);
                                    }
                                )
                                ->required()
                                ->disabled($disabled)
                                ->columnSpanFull(),
                            TextInput::make('title')
                                ->required()
                                ->maxLength(255)
                                ->disabled($disabled)
                                ->columnSpanFull(),

                            RichEditor::make('description')
                                ->required()
                                ->columnSpanFull()
                                ->disabled($disabled)
                                ->toolbarButtons([
                                    'attachFiles',
                                    'blockquote',
                                    'bold',
                                    'bulletList',
                                    'codeBlock',
                                    'h2',
                                    'h3',
                                    'italic',
                                    'link',
                                    'orderedList',
                                    'redo',
                                    'strike',
                                    'underline',
                                    'undo',
                                ]),
                        ]),

                    // Right Sidebar (1/3 width)
                    Section::make('Ticket Properties')
                        ->columnSpan(1)
                        ->schema([

                            // TextInput::make('created_at')
                            //     ->label('Created')
                            //     ->disabled()
                            //     ->formatStateUsing(fn($state) => $state ? \Carbon\Carbon::parse($state)->diffForHumans() : null)
                            //     ->columnSpanFull(),
                            // Select::make('created_by')
                            //     ->label('Created By')
                            //     ->relationship('user', 'name')
                            //     ->disabled()
                            //     ->columnSpanFull(),



                            // Select::make('status_id')
                            //     ->relationship('status', 'name')
                            //     ->required()
                            //     ->columnSpanFull(),

                            Select::make('priority')
                                ->options([
                                    'low' => 'Low',
                                    'medium' => 'Medium',
                                    'high' => 'High',
                                    'critical' => 'Critical',
                                ])
                                ->default('medium') // <-- self selected by default
                                ->disabled($disabled)
                                ->required()
                                ->columnSpanFull(),

                            Select::make('agents')
                                ->label('Assign To')
                                ->disabled(auth()->user()->cannot('Assign-to-other Ticket'))

                                ->preload()
                                ->default([auth()->id()]) // <-- self selected by default
                                ->relationship(
                                    name: 'agents',
                                    titleAttribute: 'name',
                                    modifyQueryUsing: function ($query) {
                                        $userUnitIds = auth()->user()->supportUnits->pluck('id');

                                        $query->whereHas('supportUnits', function ($q) use ($userUnitIds) {
                                            $q->whereIn('support_unit_id', $userUnitIds);
                                        });
                                    }
                                )
                                ->columnSpanFull()
                        ]),
                ])
        ];
    }

    public static function basicInfoListSchema(): array
    {
        return [
            InfolistGrid::make()
                ->columns(3)
                ->schema([
                    // Main Content (2/3 width)
                    InfolistSection::make('Ticket Details')
                        ->columnSpan(2)
                        ->schema([
                            TextEntry::make('title')
                                ->label('Title')
                                ->columnSpanFull()
                                ->size(TextEntry\TextEntrySize::Large)
                                ->formatStateUsing(fn($state, $record) => $record->ticket_id . ' ' . $state),

                            TextEntry::make('description')
                                ->label('Description')
                                ->html()
                                ->columnSpanFull()
                                ->prose() // Adds nice typography for HTML content
                                ->extraAttributes(['class' => 'max-w-none']), // Removes max-width constraint
                            ViewEntry::make('Files')
                                ->label("Attachments")
                                ->view('filament.infolists.ticket-files'),
                            \Coolsam\NestedComments\Filament\Infolists\CommentsEntry::make('comments'),


                            //->columnSpanFull(),
                            // Add more details if needed
                        ]),


                    // Right Sidebar (1/3 width)
                    InfolistSection::make('Ticket Properties')
                        ->columnSpan(1)
                        ->schema([
                            TextEntry::make('branch.name')
                                ->label('Branch')
                                ->columnSpanFull()
                                ->icon('heroicon-o-building-office'),

                            TextEntry::make('created_at')
                                ->label('Created')
                                ->formatStateUsing(fn($state) => $state ? \Carbon\Carbon::parse($state)->diffForHumans() : null)
                                ->columnSpanFull()
                                ->icon('heroicon-o-clock'),

                            TextEntry::make('user.name')
                                ->label('Created By')
                                ->columnSpanFull()
                                ->icon('heroicon-o-user'),

                            ViewEntry::make('status')
                                ->label('Status')
                                ->view('filament.infolists.change-status', [
                                    'statuses' => \App\Models\TicketStatus::whereNot('id', TicketStatusConstant::CLOSED)->get(),
                                ]),

                            TextEntry::make('priority')
                                ->label('Priority')
                                ->columnSpanFull()
                                ->badge()
                                ->color(fn($state) => match ($state) {
                                    'Low' => 'gray',
                                    'Medium' => 'info',
                                    'High' => 'warning',
                                    'Critical' => 'danger',
                                    default => 'primary',
                                })
                                ->icon(fn($state) => match ($state) {
                                    'Low' => 'heroicon-o-arrow-down',
                                    'Medium' => 'heroicon-o-minus',
                                    'High' => 'heroicon-o-arrow-up',
                                    'Critical' => 'heroicon-o-exclamation-triangle',
                                    default => null,
                                }),

                            Infolists\Components\RepeatableEntry::make('agents')
                                ->label('Assigned To')
                                ->schema([
                                    TextEntry::make('name')
                                        ->label('')
                                        ->icon('heroicon-o-user-circle'),
                                ])
                                ->columnSpanFull()
                                ->grid(1)
                                ->contained(false), // Removes container padding for tighter layout

                            //add a text box for change status
                            ViewEntry::make('History')
                                ->label('History')
                                ->view('filament.infolists.ticket-history')

                        ])
                        ->extraAttributes(['class' => 'bg-gray-50 dark:bg-gray-800 p-0 rounded-lg']) // Subtle background
                    //->collapsible(),


                ]),
        ];
    }
}
