<?php

namespace App\Filament\Resources\AlertIncidents\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AlertIncidentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('alert_rule_id')
                    ->required()
                    ->numeric(),
                TextInput::make('endpoint_id')
                    ->required(),
                DateTimePicker::make('triggered_at')
                    ->required(),
                DateTimePicker::make('resolved_at'),
                Textarea::make('message')
                    ->columnSpanFull(),
            ]);
    }
}
