<?php

namespace App\Filament\Resources\AlertIncidents\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AlertIncidentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('alert_rule_id')
                    ->numeric(),
                TextEntry::make('endpoint_id'),
                TextEntry::make('triggered_at')
                    ->dateTime(),
                TextEntry::make('resolved_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('message')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
