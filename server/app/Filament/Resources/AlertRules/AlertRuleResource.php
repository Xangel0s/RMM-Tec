<?php

namespace App\Filament\Resources\AlertRules;

use App\Filament\Resources\AlertRules\Pages\ManageAlertRules;
use App\Models\AlertRule;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AlertRuleResource extends Resource
{
    protected static ?string $model = AlertRule::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBellAlert;

    protected static ?string $recordTitleAttribute = 'metric';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('endpoint_id')
                    ->relationship('endpoint', 'hostname')
                    ->searchable()
                    ->preload()
                    ->placeholder('Global Rule (All Endpoints)')
                    ->label('Endpoint'),

                Select::make('metric')
                    ->options([
                        'cpu' => 'CPU Usage (%)',
                        'ram' => 'RAM Usage (%)',
                        'disk' => 'Disk Usage (%)',
                        'status' => 'Status (Offline)',
                    ])
                    ->required(),

                TextInput::make('threshold')
                    ->numeric()
                    ->default(80)
                    ->label('Threshold')
                    ->helperText('Percentage for usage, or minutes for status offline'),

                TextInput::make('duration_seconds')
                    ->numeric()
                    ->default(300)
                    ->label('Duration (Seconds)')
                    ->helperText('Trigger only if condition persists for this time'),

                Select::make('action')
                    ->options([
                        'email' => 'Send Email',
                        'webhook' => 'Call Webhook',
                        'log' => 'Log Only',
                    ])
                    ->default('email')
                    ->required(),

                Toggle::make('is_active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('metric')
            ->columns([
                TextColumn::make('endpoint.hostname')
                    ->label('Endpoint')
                    ->placeholder('Global'),

                TextColumn::make('metric')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'cpu' => 'danger',
                        'ram' => 'warning',
                        'disk' => 'info',
                        'status' => 'gray',
                        default => 'primary',
                    }),

                TextColumn::make('threshold'),

                TextColumn::make('duration_seconds')->label('Duration (s)'),

                TextColumn::make('action'),

                IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageAlertRules::route('/'),
        ];
    }
}
