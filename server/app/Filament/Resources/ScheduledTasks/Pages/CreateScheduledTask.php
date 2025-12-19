<?php

namespace App\Filament\Resources\ScheduledTasks\Pages;

use App\Filament\Resources\ScheduledTasks\ScheduledTaskResource;
use Filament\Resources\Pages\CreateRecord;

class CreateScheduledTask extends CreateRecord
{
    protected static string $resource = ScheduledTaskResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Automatically assign the creator to the current user
        $data['created_by'] = auth()->id();

        return $data;
    }
}
