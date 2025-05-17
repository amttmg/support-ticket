<?php
// app/Filament/Helpers/TicketForms.php
namespace App\Filament\Helpers;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;

class TicketForms
{
    public static function basicSchema($disabled = false): array
    {
        return [
            Grid::make()
                ->columns(3) // Important: define 3 columns to support 2/3 and 1/3 layout
                ->schema([
                    // Main Content (2/3 width)
                    Section::make('Ticket Details')
                        ->columnSpan(2)
                        ->schema([
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
                            Select::make('branch_id')
                                ->relationship('branch', 'name')
                                ->required()
                                ->disabled()
                                ->columnSpanFull(),
                            TextInput::make('created_at')
                                ->label('Created')
                                ->disabled()
                                ->formatStateUsing(fn($state) => $state ? \Carbon\Carbon::parse($state)->diffForHumans() : null)
                                ->columnSpanFull(),
                            Select::make('created_by')
                                ->label('Created By')
                                ->relationship('user', 'name')
                                ->disabled()
                                ->columnSpanFull(),
                            Select::make('status_id')
                                ->relationship('status', 'name')
                                ->required()
                                ->columnSpanFull(),

                            Select::make('priority')
                                ->options([
                                    'low' => 'Low',
                                    'medium' => 'Medium',
                                    'high' => 'High',
                                    'critical' => 'Critical',
                                ])
                                ->disabled($disabled)
                                ->required()
                                ->columnSpanFull(),

                            Select::make('agents')
                                ->relationship('agents', 'name')
                                ->multiple()
                                ->preload()
                                ->label('Assign To')
                                ->disabled($disabled)
                                ->columnSpanFull(),
                        ]),
                ])
        ];
    }
}
