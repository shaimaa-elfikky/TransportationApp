<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\CompletedTripsChart;
use App\Filament\Widgets\DriverAvailabilityChart;
use App\Filament\Widgets\TripStatusBreakdownChart;
use App\Filament\Widgets\VehicleAvailabilityChart;
use Filament\Pages\Dashboard as BasePage;

class Dashboard extends BasePage
{
    public function getWidgets(): array
    {
        return [
            CompletedTripsChart::class,
            TripStatusBreakdownChart::class,
            DriverAvailabilityChart::class,
            VehicleAvailabilityChart::class,
        ];
    }

    public function getHeaderWidgetsColumns(): int|string|array
    {
        return 4;
    }
}
