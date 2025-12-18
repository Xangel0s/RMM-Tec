<?php

namespace App\Filament\Resources\Endpoints\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
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
                    ->formatStateUsing(fn ($state) => $state . '%')
                    ->color(fn ($state) => $state > 80 ? 'danger' : ($state > 50 ? 'warning' : 'success')),

                TextColumn::make('hardware_summary.ram_used_percent')
                    ->label('RAM')
                    ->formatStateUsing(fn ($state) => $state . '%')
                    ->color(fn ($state) => $state > 80 ? 'danger' : ($state > 50 ? 'warning' : 'success')),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'online' => 'success',
                        'maintenance' => 'warning',
                        'offline' => 'danger',
                    }),
                
                TextColumn::make('last_seen_at')
                    ->label('Visto')
                    // Removed since() to avoid intl dependency
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->poll('5s');
    }
}
