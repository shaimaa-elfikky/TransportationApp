<?php

namespace App\Filament\Widgets;

use App\Enums\TripStatus;
use App\Models\Driver;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Livewire\Attributes\Computed;

class DriverAvailabilityChart extends ChartWidget
{
    protected static ?string $heading = 'Driver Availability';

    protected static ?string $maxHeight = '300px';

    protected static ?string $emptyStateMessage = 'No driver data to display.';

    #[Computed(cache: true, seconds: 60)]
    protected function getData(): array
    {
        $totalDrivers = Driver::query()->count();

        $now = Carbon::now();

        $availableDrivers = 0;
        if ($totalDrivers > 0) {
            $availableDrivers = Driver::query()
                ->whereDoesntHave('trips', function ($query) use ($now) {
                    $query
                        ->where('start_time', '<=', $now)
                        ->where('end_time', '>=', $now)
                        ->whereIn('status', [TripStatus::Scheduled, TripStatus::Started]);
                })
                ->count();
        }

        $driversOnTrip = max(0, $totalDrivers - $availableDrivers);

        return [
            'datasets' => [
                [
                    'label' => 'Drivers',
                    'data' => [max(0, $availableDrivers), max(0, $driversOnTrip)],
                    'backgroundColor' => [
                        'rgba(34, 197, 94, 0.7)',
                        'rgba(245, 158, 11, 0.7)',
                    ],
                ],
            ],
            'labels' => ['Available', 'On a Trip'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
