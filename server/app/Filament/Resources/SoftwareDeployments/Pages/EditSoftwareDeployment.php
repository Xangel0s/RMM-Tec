<?php

namespace App\Filament\Resources\SoftwareDeployments\Pages;

use App\Filament\Resources\SoftwareDeployments\SoftwareDeploymentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSoftwareDeployment extends EditRecord
{
    protected static string $resource = SoftwareDeploymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
