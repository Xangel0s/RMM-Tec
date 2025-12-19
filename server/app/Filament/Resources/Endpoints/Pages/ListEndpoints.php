<?php

namespace App\Filament\Resources\Endpoints\Pages;

use App\Filament\Resources\Endpoints\EndpointResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEndpoints extends ListRecords
{
    protected static string $resource = EndpointResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('connect_device')
                ->label('Connect New Device')
                ->icon('heroicon-o-plus-circle')
                ->color('primary')
                ->modalHeading('Connect a New Device')
                ->modalDescription('Run the following command on the target device to install the agent and connect it to this dashboard.')
                ->modalContent(view('filament.pages.actions.connect-device-modal'))
                ->modalSubmitAction(false) // Hide submit button as it's just informational
                ->modalCancelActionLabel('Close'),
            CreateAction::make(),
        ];
    }
}
