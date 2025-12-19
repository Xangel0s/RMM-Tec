<?php

namespace App\Filament\Resources\Endpoints\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EndpointForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->schema([
                        Section::make('General Information')
                            ->description('Basic device details')
                            ->icon('heroicon-o-computer-desktop')
                            ->schema([
                                TextInput::make('hostname')
                                    ->required()
                                    ->maxLength(255),
                                Select::make('status')
                                    ->options([
                                        'online' => 'Online',
                                        'offline' => 'Offline',
                                        'maintenance' => 'Maintenance',
                                    ])
                                    ->required()
                                    ->default('offline'),
                                TextInput::make('os_info')
                                    ->label('Operating System')
                                    ->placeholder('Windows 10 Pro'),
                            ])->columnSpan(2),

                        Section::make('Connection Details')
                            ->description('Network & Remote Access')
                            ->icon('heroicon-o-signal')
                            ->schema([
                                TextInput::make('public_ip')
                                    ->label('Public IP')
                                    ->ipv4(),
                                TextInput::make('local_ip')
                                    ->label('Local IP')
                                    ->ipv4(),
                                TextInput::make('rustdesk_id')
                                    ->label('RustDesk ID')
                                    ->helperText('ID used for remote connection via RustDesk')
                                    ->numeric(),
                            ])->columnSpan(1),
                    ]),

                Section::make('Security & Auth')
                    ->schema([
                        TextInput::make('api_token')
                            ->label('Agent API Token')
                            ->disabled()
                            ->dehydrated(false) // Don't save if disabled
                            ->helperText('Token used by the agent to authenticate. Generated automatically.'),
                    ])->collapsed(),
            ]);
    }
}
