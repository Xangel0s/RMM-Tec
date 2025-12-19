<?php

namespace App\Filament\Resources\SoftwareInventories;

use App\Filament\Resources\SoftwareInventories\Pages\CreateSoftwareInventory;
use App\Filament\Resources\SoftwareInventories\Pages\EditSoftwareInventory;
use App\Filament\Resources\SoftwareInventories\Pages\ListSoftwareInventories;
use App\Filament\Resources\SoftwareInventories\Schemas\SoftwareInventoryForm;
use App\Filament\Resources\SoftwareInventories\Tables\SoftwareInventoriesTable;
use App\Models\SoftwareInventory;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class SoftwareInventoryResource extends Resource
{
    protected static ?string $model = SoftwareInventory::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Software Management';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'software_name';

    public static function form(Schema $schema): Schema
    {
        return SoftwareInventoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SoftwareInventoriesTable::configure($table);
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
            'index' => ListSoftwareInventories::route('/'),
            'create' => CreateSoftwareInventory::route('/create'),
            'edit' => EditSoftwareInventory::route('/{record}/edit'),
        ];
    }
}
