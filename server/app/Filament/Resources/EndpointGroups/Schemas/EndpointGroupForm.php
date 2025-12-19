<?php

namespace App\Filament\Resources\EndpointGroups\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EndpointGroupForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                Textarea::make('endpoints')
                    ->columnSpanFull(),
            ]);
    }
}
