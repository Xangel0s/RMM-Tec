<?php

namespace App\Filament\Resources\RustdeskSettings\Pages;

use App\Filament\Resources\RustdeskSettings\RustdeskSettingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRustdeskSetting extends EditRecord
{
    protected static string $resource = RustdeskSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
