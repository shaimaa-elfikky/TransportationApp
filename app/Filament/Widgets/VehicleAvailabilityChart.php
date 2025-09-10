<?php

namespace App\Filament\Widgets;

use App\Enums\TripStatus;
use App\Models\Vehicle;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Livewire\Attributes\Computed;

class VehicleAvailabilityChart extends ChartWidget
{
    protected static ?string $heading = 'Vehicle Availability';

    protected static ?string $maxHeight = '300px';

    protected static ?string $emptyStateMessage = 'No vehicle data to display.';

    #[Computed(cache: true, seconds: 60)]
    protected function getData(): array
    {
        $totalVehicles = Vehicle::query()->count();
        $now = Carbon::now();

        $availableVehicles = 0;
        if ($totalVehicles > 0) {
            $availableVehicles = Vehicle::query()
                ->whereDoesntHave('trips', function ($query) use ($now) {
                    $query
                        ->where('start_time', '<=', $now)
                        ->where('end_time', '>=', $now)
                        ->whereIn('status', [TripStatus::Scheduled, TripStatus::Started]);
                })
                ->count();
        }

        $vehiclesOnTrip = max(0, $totalVehicles - $availableVehicles);

        return [
            'datasets' => [
                [
                    'label' => 'Vehicles',
                    'data' => [max(0, $availableVehicles), max(0, $vehiclesOnTrip)],
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
