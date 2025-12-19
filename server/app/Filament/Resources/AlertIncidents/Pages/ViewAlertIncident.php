<?php

namespace App\Filament\Resources\AlertIncidents\Pages;

use App\Filament\Resources\AlertIncidents\AlertIncidentResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewAlertIncident extends ViewRecord
{
    protected static string $resource = AlertIncidentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
