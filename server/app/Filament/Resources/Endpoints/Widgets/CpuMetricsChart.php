<?php

namespace App\Filament\Resources\Endpoints\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Model;

class CpuMetricsChart extends ChartWidget
{
    public function getHeading(): ?string
    {
        return 'CPU';
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
            ->take(30) // Show last 30 points for better granularity like the image
            ->get()
            ->reverse(); // Chronological order

        return [
            'datasets' => [
                [
                    'label' => 'CPU Usage',
                    'data' => $metrics->pluck('cpu_usage')->toArray(),
                    'borderColor' => '#4ade80', // Bright Green
                    'backgroundColor' => 'rgba(74, 222, 128, 0.1)', // Very subtle fill
                    'borderWidth' => 2,
                    'fill' => 'start',
                    'tension' => 0.4,
                    'pointRadius' => 0, // Hide points for clean line look
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
                        'color' => '#9ca3af', // Gray-400
                        'stepSize' => 20,
                    ],
                    'grid' => [
                        'color' => '#374151', // Gray-700 (Subtle)
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
