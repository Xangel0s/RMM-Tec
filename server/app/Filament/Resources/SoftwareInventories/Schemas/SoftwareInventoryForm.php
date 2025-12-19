<?php

namespace App\Filament\Resources\SoftwareInventories\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SoftwareInventoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('endpoint_id')
                    ->required(),
                TextInput::make('software_name')
                    ->required(),
                TextInput::make('version'),
                DateTimePicker::make('install_date'),
                TextInput::make('publisher'),
                DateTimePicker::make('synced_at'),
            ]);
    }
}
