<?php

namespace App\Filament\Resources\Endpoints\Pages;

use App\Filament\Resources\Endpoints\EndpointResource;
use App\Filament\Resources\Endpoints\Widgets\CpuMetricsChart;
use App\Filament\Resources\Endpoints\Widgets\MemoryMetricsChart;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEndpoint extends EditRecord
{
    protected static string $resource = EndpointResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('processes')
                ->label('Procesos')
                ->icon('heroicon-o-cpu-chip')
                ->url(fn () => EndpointResource::getUrl('processes', ['record' => $this->record])),
            Action::make('files')
                ->label('Archivos')
                ->icon('heroicon-o-folder')
                ->url(fn () => EndpointResource::getUrl('files', ['record' => $this->record])),
            DeleteAction::make(),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            CpuMetricsChart::class,
            MemoryMetricsChart::class,
        ];
    }
}
