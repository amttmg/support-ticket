<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Password;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_type'] = 'back';
        return $data;
    }
    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $record = parent::handleRecordCreation($data);

        Password::broker(Filament::getAuthPasswordBroker())
            ->sendResetLink(
                ['email' => $record->email],
                function ($user, string $token) {
                    $user->sendPasswordResetNotification($token);
                }
            );

        return $record;
    }
}
