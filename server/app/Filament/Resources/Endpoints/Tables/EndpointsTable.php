<?php

namespace App\Filament\Resources\Endpoints\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EndpointsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('hostname')->searchable()->sortable()->weight('bold'),
                TextColumn::make('local_ip')->label('IP Local'),

                // Telemetría sin formato numérico dependiente de intl
                TextColumn::make('hardware_summary.cpu_usage_percent')
                    ->label('CPU')
                    ->formatStateUsing(fn ($state) => $state.'%')
                    ->color(fn ($state) => $state > 80 ? 'danger' : ($state > 50 ? 'warning' : 'success')),

                TextColumn::make('hardware_summary.ram_used_percent')
                    ->label('RAM')
                    ->formatStateUsing(fn ($state) => $state.'%')
                    ->color(fn ($state) => $state > 80 ? 'danger' : ($state > 50 ? 'warning' : 'success')),

                TextColumn::make('rustdesk_id')
                    ->label('RustDesk ID')
                    ->copyable()
                    ->searchable(),

                TextColumn::make('status')
                    ->badge()
                    ->getStateUsing(function ($record) {
                        return $record->last_seen_at && $record->last_seen_at->diffInMinutes(now()) < 5
                            ? 'online'
                            : 'offline';
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'online' => 'success',
                        'maintenance' => 'warning',
                        'offline' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('last_seen_at')
                    ->label('Visto')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('files')
                    ->label('Archivos')
                    ->icon('heroicon-o-folder')
                    ->url(fn ($record) => route('filament.admin.resources.endpoints.files', $record)),

                Action::make('processes')
                    ->label('Procesos')
                    ->icon('heroicon-o-cpu-chip')
                    ->url(fn ($record) => route('filament.admin.resources.endpoints.processes', $record)),

                Action::make('connect')
                    ->label('Conectar')
                    ->icon('heroicon-o-computer-desktop')
                    ->color(Color::Sky)
                    ->url(fn ($record) => $record->rustdesk_id ? "rustdesk://{$record->rustdesk_id}" : null)
                    ->openUrlInNewTab(false)
                    ->visible(fn ($record) => ! empty($record->rustdesk_id)),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->poll('5s');
    }
}
