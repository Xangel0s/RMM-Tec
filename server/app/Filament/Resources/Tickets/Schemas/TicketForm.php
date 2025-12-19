<?php

namespace App\Filament\Resources\Tickets\Schemas;

use App\Models\Endpoint;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TicketForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->minLength(3)
                    ->maxLength(150)
                    ->live()
                    ->validationAttribute('title')
                    ->validationMessages([
                        'required' => 'The title is required',
                        'min' => 'Title must be at least :min characters',
                        'max' => 'Title must be at most :max characters',
                    ])
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->maxLength(3000)
                    ->live()
                    ->validationAttribute('description')
                    ->validationMessages([
                        'max' => 'Description must be at most :max characters',
                    ])
                    ->columnSpanFull(),
                Select::make('status')
                    ->options([
                        'open' => 'Open',
                        'in_progress' => 'In Progress',
                        'resolved' => 'Resolved',
                    ])
                    ->required()
                    ->live()
                    ->validationAttribute('status')
                    ->validationMessages([
                        'required' => 'Status is required',
                    ])
                    ->default('open'),
                Select::make('priority')
                    ->options([
                        'low' => 'Low',
                        'medium' => 'Medium',
                        'high' => 'High',
                    ])
                    ->required()
                    ->live()
                    ->validationAttribute('priority')
                    ->validationMessages([
                        'required' => 'Priority is required',
                    ])
                    ->default('medium'),
                Select::make('user_id')
                    ->label('Requester')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->live()
                    ->validationAttribute('requester')
                    ->validationMessages([
                        'required' => 'Requester is required',
                    ])
                    ->default(auth()->id()),
                Select::make('assigned_to')
                    ->label('Assigned Technician')
                    ->relationship('assignee', 'name')
                    ->searchable()
                    ->live()
                    ->preload(),
                Select::make('endpoint_id')
                    ->label('Related Endpoint')
                    ->options(Endpoint::query()->pluck('hostname', 'id')->toArray())
                    ->searchable()
                    ->live()
                    ->preload(),
            ]);
    }
}
