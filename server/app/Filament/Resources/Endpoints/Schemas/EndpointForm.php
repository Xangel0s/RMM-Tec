<?php

namespace App\Filament\Resources\Endpoints\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EndpointForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('hostname')->required(),
                TextInput::make('api_token')->disabled(),
                TextInput::make('status')->required(),
                TextInput::make('public_ip'),
                TextInput::make('local_ip'),
            ]);
    }
}
