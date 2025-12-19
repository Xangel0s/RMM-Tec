<?php

namespace App\Filament\Resources\SoftwareDeployments;

use App\Filament\Resources\SoftwareDeployments\Pages\CreateSoftwareDeployment;
use App\Filament\Resources\SoftwareDeployments\Pages\EditSoftwareDeployment;
use App\Filament\Resources\SoftwareDeployments\Pages\ListSoftwareDeployments;
use App\Filament\Resources\SoftwareDeployments\Schemas\SoftwareDeploymentForm;
use App\Filament\Resources\SoftwareDeployments\Tables\SoftwareDeploymentsTable;
use App\Models\SoftwareDeployment;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class SoftwareDeploymentResource extends Resource
{
    protected static ?string $model = SoftwareDeployment::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Software Management';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return SoftwareDeploymentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SoftwareDeploymentsTable::configure($table);
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
            'index' => ListSoftwareDeployments::route('/'),
            'create' => CreateSoftwareDeployment::route('/create'),
            'edit' => EditSoftwareDeployment::route('/{record}/edit'),
        ];
    }
}
