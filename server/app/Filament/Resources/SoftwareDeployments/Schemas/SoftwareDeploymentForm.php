<?php

namespace App\Filament\Resources\SoftwareDeployments\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SoftwareDeploymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('software_name')
                    ->required(),
                TextInput::make('installer_url')
                    ->url()
                    ->required(),
                Textarea::make('endpoints')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
                DateTimePicker::make('deployment_date'),
            ]);
    }
}
