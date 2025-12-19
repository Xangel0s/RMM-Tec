<?php

namespace App\Filament\Resources\SoftwareInventories\Pages;

use App\Filament\Resources\SoftwareInventories\SoftwareInventoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSoftwareInventory extends EditRecord
{
    protected static string $resource = SoftwareInventoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
