<?php

namespace App\Filament\Widgets;

use App\Models\Metric;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class EquipmentStatusChart extends ChartWidget
{
    protected ?string $heading = 'Avg CPU Usage (Last 24h)';

    protected ?string $pollingInterval = '60s';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        // Check if we have metrics, otherwise return empty to avoid errors
        if (Metric::count() === 0) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $data = Trend::model(Metric::class)
            ->between(
                start: now()->subDay(),
                end: now(),
            )
            ->perHour()
            ->average('cpu_usage');

        return [
            'datasets' => [
                [
                    'label' => 'CPU Usage %',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'borderColor' => '#3b82f6',
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
