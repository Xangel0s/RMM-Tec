<?php

namespace App\Filament\Resources\RustdeskSettings\Pages;

use App\Filament\Resources\RustdeskSettings\RustdeskSettingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRustdeskSettings extends ListRecords
{
    protected static string $resource = RustdeskSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
