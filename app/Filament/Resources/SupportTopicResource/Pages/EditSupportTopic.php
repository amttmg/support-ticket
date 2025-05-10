<?php

namespace App\Filament\Resources\SupportTopicResource\Pages;

use App\Filament\Resources\SupportTopicResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSupportTopic extends EditRecord
{
    protected static string $resource = SupportTopicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
