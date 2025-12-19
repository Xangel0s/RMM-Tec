<?php

namespace App\Filament\Resources\AlertIncidents;

use App\Filament\Resources\AlertIncidents\Pages\CreateAlertIncident;
use App\Filament\Resources\AlertIncidents\Pages\EditAlertIncident;
use App\Filament\Resources\AlertIncidents\Pages\ListAlertIncidents;
use App\Filament\Resources\AlertIncidents\Pages\ViewAlertIncident;
use App\Filament\Resources\AlertIncidents\Schemas\AlertIncidentForm;
use App\Filament\Resources\AlertIncidents\Schemas\AlertIncidentInfolist;
use App\Filament\Resources\AlertIncidents\Tables\AlertIncidentsTable;
use App\Models\AlertIncident;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class AlertIncidentResource extends Resource
{
    protected static ?string $model = AlertIncident::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Monitoring & Alerts';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'message';

    public static function form(Schema $schema): Schema
    {
        return AlertIncidentForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AlertIncidentInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AlertIncidentsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAlertIncidents::route('/'),
            'create' => CreateAlertIncident::route('/create'),
            'view' => ViewAlertIncident::route('/{record}'),
            'edit' => EditAlertIncident::route('/{record}/edit'),
        ];
    }
}
