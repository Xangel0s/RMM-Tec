<?php

namespace App\Filament\Resources\AlertIncidents\Pages;

use App\Filament\Resources\AlertIncidents\AlertIncidentResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditAlertIncident extends EditRecord
{
    protected static string $resource = AlertIncidentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
