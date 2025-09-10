<?php

namespace App\Filament\Widgets;

use App\Enums\TripStatus;
use App\Models\Vehicle;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class VehicleAvailabilityChart extends ChartWidget
{
    protected static ?string $heading = 'Vehicle Availability';
    protected static ?string $maxHeight = '300px';
    protected static ?string $emptyStateMessage = 'No vehicle data to display.';

    protected function getData(): array
    {
        $totalVehicles = Vehicle::query()->count();
        if ($totalVehicles === 0) {
            return []; 
        }

        $now = Carbon::now();
        
  
        $availableVehicles = Vehicle::query()
            ->whereDoesntHave('trips', function ($query) use ($now) {
                $query
                    ->where('start_time', '<=', $now)
                    ->where('end_time', '>=', $now)
                    ->whereIn('status', [TripStatus::Scheduled, TripStatus::Started]);
            })
            ->count();
        

        $vehiclesOnTrip = $totalVehicles - $availableVehicles;

        return [
            'datasets' => [
                [
                    'label' => 'Vehicles',
                    'data' => [$availableVehicles, $vehiclesOnTrip],
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
        return 'donut';
    }
}