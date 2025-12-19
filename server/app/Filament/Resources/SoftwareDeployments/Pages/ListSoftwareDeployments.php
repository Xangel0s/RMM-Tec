<?php

namespace App\Filament\Resources\SoftwareDeployments\Pages;

use App\Filament\Resources\SoftwareDeployments\SoftwareDeploymentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSoftwareDeployments extends ListRecords
{
    protected static string $resource = SoftwareDeploymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
