<?php

namespace App\Filament\Resources\EndpointGroups\Pages;

use App\Filament\Resources\EndpointGroups\EndpointGroupResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEndpointGroups extends ListRecords
{
    protected static string $resource = EndpointGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
