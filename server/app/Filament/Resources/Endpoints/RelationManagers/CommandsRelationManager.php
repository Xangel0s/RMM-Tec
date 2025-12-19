<?php

namespace App\Filament\Resources\Endpoints\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction; // Changed from Filament\Forms\Form
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class CommandsRelationManager extends RelationManager
{
    protected static string $relationship = 'commands';

    protected static ?string $title = 'Comandos';

    public function form(Schema $schema): Schema // Changed signature
    {
        return $schema
            ->components([ // Changed from schema([]) to components([])
                Forms\Components\Textarea::make('command')
                    ->label('Comando (CMD/Powershell)')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                // Ocultamos el status y output al crear, solo los mostramos al editar/ver
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pendiente',
                        'running' => 'Ejecutando',
                        'completed' => 'Completado',
                        'failed' => 'Fallido',
                    ])
                    ->default('pending')
                    ->visibleOn(['view', 'edit']),

                Forms\Components\Textarea::make('output')
                    ->label('Salida de Consola')
                    ->columnSpanFull()
                    ->visibleOn(['view', 'edit'])
                    ->rows(10),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('command')
            ->columns([
                Tables\Columns\TextColumn::make('command')
                    ->label('Comando')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'running' => 'info',
                        'completed' => 'success',
                        'failed' => 'danger',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado')
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Ejecutar Comando'),
            ])
            ->actions([
                ViewAction::make(),
                DeleteAction::make(),
            ])
            ->poll('5s') // Auto-refrescar para ver cuando el comando termine
            ->defaultSort('created_at', 'desc');
    }
}
