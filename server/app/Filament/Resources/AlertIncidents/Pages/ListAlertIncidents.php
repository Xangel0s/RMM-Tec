<?php

namespace App\Filament\Resources\AlertIncidents\Pages;

use App\Filament\Resources\AlertIncidents\AlertIncidentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAlertIncidents extends ListRecords
{
    protected static string $resource = AlertIncidentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
