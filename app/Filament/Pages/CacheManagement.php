<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Artisan;

class CacheManagement extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.cache-management';

    public static function getNavigationGroup(): ?string
    {
        //return MenuGroups::USERSETUP;
        return __(config('filament-spatie-roles-permissions.navigation_section_group', 'filament-spatie-roles-permissions::filament-spatie.section.roles_and_permissions'));
    }


    public static function canAccess(): bool
    {
        return auth()->user()?->hasRole('Super Admin');
    }
    protected function getHeaderActions(): array
    {
        return [
            Action::make('clear_cache')
                ->label('Clear Selected Caches')
                ->form([
                    CheckboxList::make('caches')
                        ->options([
                            'cache' => 'Application Cache',
                            'view' => 'Compiled Views',
                            'route' => 'Route Cache',
                            'config' => 'Config Cache',
                            'optimize' => 'Optimized Classes',
                        ])
                        ->required()
                ])
                ->action(function (array $data) {
                    foreach ($data['caches'] as $cache) {
                        Artisan::call($cache . ':clear');
                    }

                    Notification::make()
                        ->title("Cache Cleared")
                        ->body('Selected caches have been cleared successfully.');
                })
        ];
    }
}
