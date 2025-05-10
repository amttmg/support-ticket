<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupportTopicResource\Pages\CreateSupportTopic;
use App\Filament\Resources\SupportTopicResource\Pages\EditSupportTopic;
use App\Filament\Resources\SupportTopicResource\Pages\ListSupportTopics;
use App\Models\SupportTopic;
use App\Models\CustomField;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SupportTopicResource extends Resource
{
    protected static ?string $model = SupportTopic::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('support_unit_id')
                    ->relationship('supportUnit', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->createOptionForm([
                        Forms\Components\Select::make('department_id')
                            ->relationship('department', 'name')
                            ->required(),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                    ]),

                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                // Custom Fields Section
                Forms\Components\Section::make('Custom Fields')
                    ->schema([
                        Forms\Components\Repeater::make('customFields')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('label')
                                    ->required()
                                    ->maxLength(255),

                                Forms\Components\Select::make('field_type')
                                    ->options([
                                        'text' => 'Text',
                                        'textarea' => 'Text Area',
                                        'select' => 'Dropdown',
                                        'checkbox' => 'Checkbox',
                                        'radio' => 'Radio Buttons',
                                        'file' => 'File Upload',
                                        'date' => 'Date',
                                    ])
                                    ->required()
                                    ->live(),

                                Forms\Components\Textarea::make('options')
                                    ->rows(3)
                                    ->helperText('For select, radio, checkbox. Enter options comma separated.')
                                    ->visible(fn(Forms\Get $get) => in_array($get('field_type'), ['select', 'radio', 'checkbox'])),

                                Forms\Components\Toggle::make('is_required')
                                    ->inline(false),

                                // Forms\Components\TextInput::make('sort')
                                //     ->numeric()
                                //     ->default(0),
                            ])
                            ->columns(2)
                            ->itemLabel(fn(array $state): ?string => $state['label'] ?? null)
                            ->addActionLabel('Add Custom Field')
                            ->collapsible()
                            ->collapsed()
                            ->orderable()
                            ->defaultItems(0),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('supportUnit.name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('customFields.count')
                    ->label('Custom Fields')
                    ->counts('customFields'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('supportUnit')
                    ->relationship('supportUnit', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // You could add a relation manager for tickets if needed
            // RelationManagers\TicketsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSupportTopics::route('/'),
            'create' => CreateSupportTopic::route('/create'),
            //'view' => Pages\ViewSupportTopic::route('/{record}'),
            'edit' => EditSupportTopic::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
