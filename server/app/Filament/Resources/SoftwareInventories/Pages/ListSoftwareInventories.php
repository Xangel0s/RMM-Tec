<?php

namespace App\Filament\Resources\SoftwareInventories\Pages;

use App\Filament\Resources\SoftwareInventories\SoftwareInventoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSoftwareInventories extends ListRecords
{
    protected static string $resource = SoftwareInventoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
