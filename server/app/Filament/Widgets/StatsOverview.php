<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Endpoints\EndpointResource;
use App\Filament\Resources\Tickets\TicketResource;
use App\Models\Endpoint;
use App\Models\Ticket;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $totalEndpoints = Endpoint::count();
        $onlineEndpoints = Endpoint::where('last_seen_at', '>=', now()->subMinutes(5))->count();
        $offlineEndpoints = $totalEndpoints - $onlineEndpoints;
        $openTickets = Ticket::where('status', 'open')->count();

        return [
            Stat::make('Total Endpoints', $totalEndpoints)
                ->description('Registered devices')
                ->descriptionIcon('heroicon-m-computer-desktop')
                ->color('primary')
                ->url(EndpointResource::getUrl('index')),
            Stat::make('Online Status', "$onlineEndpoints / $totalEndpoints")
                ->description("$offlineEndpoints offline")
                ->descriptionIcon('heroicon-m-signal')
                ->color($offlineEndpoints > 0 ? 'danger' : 'success')
                ->url(EndpointResource::getUrl('index')),
            Stat::make('Open Tickets', $openTickets)
                ->description('Pending resolution')
                ->descriptionIcon('heroicon-m-ticket')
                ->color($openTickets > 5 ? 'danger' : 'warning')
                ->url(TicketResource::getUrl('index')),
        ];
    }
}
