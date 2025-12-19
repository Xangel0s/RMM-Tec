<?php

namespace App\Filament\Resources\ScheduledTasks\Pages;

use App\Filament\Resources\ScheduledTasks\ScheduledTaskResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListScheduledTasks extends ListRecords
{
    protected static string $resource = ScheduledTaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
