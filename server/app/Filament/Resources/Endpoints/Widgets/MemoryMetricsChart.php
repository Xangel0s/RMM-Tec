<?php

namespace App\Filament\Resources\Endpoints\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Model;

class MemoryMetricsChart extends ChartWidget
{
    public function getHeading(): ?string
    {
        return 'Memory';
    }

    public function getPollingInterval(): ?string
    {
        return '5s';
    }

    public ?Model $record = null;

    protected function getData(): array
    {
        if (! $this->record) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $metrics = $this->record->metrics()
            ->latest()
            ->take(30)
            ->get()
            ->reverse();

        return [
            'datasets' => [
                [
                    'label' => 'Memory Usage',
                    'data' => $metrics->pluck('ram_usage')->toArray(),
                    'borderColor' => '#3b82f6', // Blue
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)', // Blue fill
                    'borderWidth' => 2,
                    'fill' => 'start',
                    'tension' => 0.4,
                    'pointRadius' => 0,
                    'pointHoverRadius' => 4,
                ],
            ],
            'labels' => $metrics->pluck('created_at')->map(fn ($date) => $date->format('H:i:s'))->toArray(),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'min' => 0,
                    'max' => 100,
                    'ticks' => [
                        'color' => '#9ca3af',
                        'stepSize' => 20,
                    ],
                    'grid' => [
                        'color' => '#374151',
                    ],
                ],
                'x' => [
                    'ticks' => [
                        'color' => '#9ca3af',
                        'maxTicksLimit' => 5,
                    ],
                    'grid' => [
                        'display' => false,
                    ],
                ],
            ],
            'interaction' => [
                'intersect' => false,
                'mode' => 'index',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
