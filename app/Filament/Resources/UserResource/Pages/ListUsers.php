<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    function getTabs(): array
    {
        return [
            'back' => Tab::make('Backend Users')
                ->modifyQueryUsing(function (Builder $query) {
                    return $query->where('user_type', 'back');
                })
                ->icon('heroicon-o-user-circle')
                ->badge(User::where('user_type', 'back')->count()),

            'front' => Tab::make('Frontend Users')  // Fixed label from "Backend Users" to "Frontend Users"
                ->modifyQueryUsing(function (Builder $query) {
                    return $query->where('user_type', 'front');  // Fixed query from "back" to "front"
                })
                ->icon('heroicon-o-user-circle')
                ->badge(User::where('user_type', 'front')->count()),
        ];
    }
}
