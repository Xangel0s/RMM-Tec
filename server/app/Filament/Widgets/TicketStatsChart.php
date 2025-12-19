<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use Filament\Widgets\ChartWidget;

class TicketStatsChart extends ChartWidget
{
    protected ?string $heading = 'Tickets by Status';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $data = Ticket::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Tickets',
                    'data' => array_values($data),
                    'backgroundColor' => [
                        '#ef4444', // red (open)
                        '#f59e0b', // amber (in_progress)
                        '#22c55e', // green (resolved)
                    ],
                ],
            ],
            'labels' => array_keys($data),
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
