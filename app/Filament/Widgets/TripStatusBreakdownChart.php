<?php

namespace App\Filament\Widgets;

use App\Enums\TripStatus;
use App\Models\Trip;
use Filament\Widgets\ChartWidget;

class TripStatusBreakdownChart extends ChartWidget
{
    protected static ?string $heading = 'Current Trip Statuses';
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $scheduledCount = Trip::query()->where('status', TripStatus::Scheduled)->count();
        $startedCount = Trip::query()->where('status', TripStatus::Started)->count(); 

        return [
            'datasets' => [
                [
                    'label' => 'Trip Count',
                    'data' => [$scheduledCount, $startedCount],
                    'backgroundColor' => [
                        'rgba(59, 130, 246, 0.5)',
                        'rgba(245, 158, 11, 0.5)',
                    ],
                    'borderColor' => [
                        'rgb(59, 130, 246)',
                        'rgb(245, 158, 11)',
                    ],
                    'borderWidth' => 2,
                ],
            ],
            'labels' => ['Scheduled', 'Started'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}