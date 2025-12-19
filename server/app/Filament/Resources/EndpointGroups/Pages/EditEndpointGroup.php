<?php

namespace App\Filament\Resources\EndpointGroups\Pages;

use App\Filament\Resources\EndpointGroups\EndpointGroupResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEndpointGroup extends EditRecord
{
    protected static string $resource = EndpointGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
