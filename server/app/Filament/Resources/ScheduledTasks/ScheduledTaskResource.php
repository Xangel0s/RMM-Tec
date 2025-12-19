<?php

namespace App\Filament\Resources\ScheduledTasks;

use App\Filament\Resources\ScheduledTasks\Pages\CreateScheduledTask;
use App\Filament\Resources\ScheduledTasks\Pages\EditScheduledTask;
use App\Filament\Resources\ScheduledTasks\Pages\ListScheduledTasks;
use App\Filament\Resources\ScheduledTasks\Schemas\ScheduledTaskForm;
use App\Filament\Resources\ScheduledTasks\Tables\ScheduledTasksTable;
use App\Models\ScheduledTask;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class ScheduledTaskResource extends Resource
{
    protected static ?string $model = ScheduledTask::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Automation';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ScheduledTaskForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ScheduledTasksTable::configure($table);
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
            'index' => ListScheduledTasks::route('/'),
            'create' => CreateScheduledTask::route('/create'),
            'edit' => EditScheduledTask::route('/{record}/edit'),
        ];
    }
}
