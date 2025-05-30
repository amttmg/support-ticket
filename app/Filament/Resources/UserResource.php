<?php

namespace App\Filament\Resources;

use App\Constants\DepartmentTypes;
use App\Constants\MenuGroups;
use App\Filament\Resources\UserResource\Pages;
use App\Models\Department;
use App\Models\SupportUnit;
use App\Models\User;
use App\Rules\AllowedEmailDomain;
use Filament\Forms;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Spatie\Permission\Models\Role;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    public static function getNavigationGroup(): ?string
    {
        // return MenuGroups::USERSETUP;
        return __(config('filament-spatie-roles-permissions.navigation_section_group', 'filament-spatie-roles-permissions::filament-spatie.section.roles_and_permissions'));
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->required()
                    ->email()
                    ->rule(new AllowedEmailDomain())
                    ->unique(ignoreRecord: true)
                    ->validationMessages([
                        'allowed_email_domain' => 'Only RBB email addresses (@rbb.com.np) are allowed.',
                    ]),
                TextInput::make('password')
                    ->password()
                    ->required(fn($context) => $context === 'create')
                    ->minLength(8)
                    ->hiddenOn('edit'),
                Forms\Components\CheckboxList::make('roles')
                    ->relationship('roles', 'name')
                    ->options(function (Forms\Get $get) {
                        $userType = $get('user_type'); // assuming you have a user_type field
                        $guard = $userType == 'back' ? 'admin' : 'web';
                        return Role::where('guard_name', $guard)
                            ->pluck('name', 'id')
                            ->toArray();
                    })
                    ->label('Roles'),
                // Department Checkbox List
                // New line using a Section
                Section::make('Support Units')
                    ->schema([
                        CheckboxList::make('Support Units')
                            ->label('')
                            ->relationship('supportUnits', 'name')
                            ->options(
                                SupportUnit::pluck('name', 'id')
                            )
                            ->columns(2)
                            ->bulkToggleable()

                    ])
                    ->collapsible()
                    ->hidden(function (Forms\Get $get) {
                        $userType = $get('user_type'); // assuming you have a user_type field

                        return $userType == 'front';
                    }), // Optional: Allows collapsing
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            //->query(User::query()->where('user_type', 'back'))
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('email')->sortable()->searchable(),

                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('user_type')
                    ->options([
                        'back' => 'Backend',
                        'front' => 'Frontend',
                    ])
                    ->label('User Type')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
