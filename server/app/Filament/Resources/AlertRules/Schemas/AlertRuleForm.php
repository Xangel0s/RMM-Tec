<?php

namespace App\Filament\Resources\AlertRules\Schemas;

use App\Models\Endpoint;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AlertRuleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('endpoint_id')
                    ->label('Endpoint')
                    ->placeholder('Global Rule')
                    ->options(Endpoint::query()->pluck('hostname', 'id'))
                    ->searchable()
                    ->preload(),
                Select::make('metric')
                    ->options([
                        'cpu' => 'CPU Usage',
                        'ram' => 'RAM Usage',
                        'disk' => 'Disk Usage',
                        'network' => 'Network Traffic',
                        'status' => 'Device Offline',
                    ])
                    ->required(),
                TextInput::make('threshold')
                    ->required()
                    ->numeric()
                    ->default(80)
                    ->suffix('%'),
                TextInput::make('duration_seconds')
                    ->required()
                    ->numeric()
                    ->default(300)
                    ->suffix('seconds'),
                Select::make('action')
                    ->options([
                        'email' => 'Send Email',
                        'webhook' => 'Call Webhook',
                        'sms' => 'Send SMS',
                    ])
                    ->required()
                    ->default('email'),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
