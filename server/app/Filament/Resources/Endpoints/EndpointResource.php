<?php

namespace App\Filament\Resources\Endpoints;

use App\Filament\Resources\Endpoints\Pages\CreateEndpoint;
use App\Filament\Resources\Endpoints\Pages\EditEndpoint;
use App\Filament\Resources\Endpoints\Pages\ListEndpoints;
use App\Filament\Resources\Endpoints\Pages\ManageProcesses;
use App\Filament\Resources\Endpoints\Pages\ManageFiles;
use App\Filament\Resources\Endpoints\Schemas\EndpointForm;
use App\Filament\Resources\Endpoints\Tables\EndpointsTable;
use App\Filament\Resources\Endpoints\RelationManagers\CommandsRelationManager;
use App\Models\Endpoint;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EndpointResource extends Resource
{
    protected static ?string $model = Endpoint::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'rmm';

    public static function form(Schema $schema): Schema
    {
        return EndpointForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EndpointsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            CommandsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEndpoints::route('/'),
            'create' => CreateEndpoint::route('/create'),
            'edit' => EditEndpoint::route('/{record}/edit'),
            'processes' => ManageProcesses::route('/{record}/processes'),
            'files' => ManageFiles::route('/{record}/files'),
        ];
    }
}
