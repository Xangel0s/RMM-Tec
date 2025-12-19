<?php

namespace App\Filament\Resources\RustdeskSettings\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class RustdeskSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('server_url')
                    ->url()
                    ->required(),
                TextInput::make('relay_server'),
                TextInput::make('api_key'),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
