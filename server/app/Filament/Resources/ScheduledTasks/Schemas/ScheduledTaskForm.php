<?php

namespace App\Filament\Resources\ScheduledTasks\Schemas;

use App\Models\Endpoint;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ScheduledTaskForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                Textarea::make('command')
                    ->required()
                    ->columnSpanFull()
                    ->helperText('Powershell or Bash command to execute'),
                TextInput::make('cron_expression')
                    ->required()
                    ->placeholder('0 2 * * 0')
                    ->helperText('Cron format: * * * * *'),
                Select::make('endpoints')
                    ->multiple()
                    ->options(Endpoint::query()->pluck('hostname', 'id'))
                    ->searchable()
                    ->preload()
                    ->columnSpanFull(),
                Toggle::make('enabled')
                    ->required()
                    ->default(true),
                DateTimePicker::make('last_run_at')
                    ->disabled(),
                DateTimePicker::make('next_run_at')
                    ->disabled(),
            ]);
    }
}
