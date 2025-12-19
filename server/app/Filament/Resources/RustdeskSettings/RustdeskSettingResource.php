<?php

namespace App\Filament\Resources\RustdeskSettings;

use App\Filament\Resources\RustdeskSettings\Pages\CreateRustdeskSetting;
use App\Filament\Resources\RustdeskSettings\Pages\EditRustdeskSetting;
use App\Filament\Resources\RustdeskSettings\Pages\ListRustdeskSettings;
use App\Filament\Resources\RustdeskSettings\Schemas\RustdeskSettingForm;
use App\Filament\Resources\RustdeskSettings\Tables\RustdeskSettingsTable;
use App\Models\RustdeskSetting;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class RustdeskSettingResource extends Resource
{
    protected static ?string $model = RustdeskSetting::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Integrations';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'server_url';

    public static function form(Schema $schema): Schema
    {
        return RustdeskSettingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RustdeskSettingsTable::configure($table);
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
            'index' => ListRustdeskSettings::route('/'),
            'create' => CreateRustdeskSetting::route('/create'),
            'edit' => EditRustdeskSetting::route('/{record}/edit'),
        ];
    }
}
