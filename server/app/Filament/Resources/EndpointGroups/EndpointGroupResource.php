<?php

namespace App\Filament\Resources\EndpointGroups;

use App\Filament\Resources\EndpointGroups\Pages\CreateEndpointGroup;
use App\Filament\Resources\EndpointGroups\Pages\EditEndpointGroup;
use App\Filament\Resources\EndpointGroups\Pages\ListEndpointGroups;
use App\Filament\Resources\EndpointGroups\Schemas\EndpointGroupForm;
use App\Filament\Resources\EndpointGroups\Tables\EndpointGroupsTable;
use App\Models\EndpointGroup;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class EndpointGroupResource extends Resource
{
    protected static ?string $model = EndpointGroup::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Device Management';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return EndpointGroupForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EndpointGroupsTable::configure($table);
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
            'index' => ListEndpointGroups::route('/'),
            'create' => CreateEndpointGroup::route('/create'),
            'edit' => EditEndpointGroup::route('/{record}/edit'),
        ];
    }
}
