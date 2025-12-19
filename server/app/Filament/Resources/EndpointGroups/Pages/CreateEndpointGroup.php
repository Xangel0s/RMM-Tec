<?php

namespace App\Filament\Resources\EndpointGroups\Pages;

use App\Filament\Resources\EndpointGroups\EndpointGroupResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEndpointGroup extends CreateRecord
{
    protected static string $resource = EndpointGroupResource::class;
}
