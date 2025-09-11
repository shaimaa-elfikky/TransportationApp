<?php

use App\Filament\Pages\Dashboard;
use App\Filament\Pages\ResourceAvailability;
use App\Filament\Resources\CompanyResource;
use App\Filament\Resources\DriverResource;
use App\Filament\Resources\TripResource;
use App\Filament\Resources\UserResource;
use App\Filament\Resources\VehicleResource;
use App\Filament\Widgets\CompletedTripsChart;
use App\Filament\Widgets\DriverAvailabilityChart;
use App\Filament\Widgets\TripStatusBreakdownChart;
use App\Filament\Widgets\VehicleAvailabilityChart;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function resources(): array
    {
        return [
           CompanyResource::class,
            DriverResource::class,
            UserResource::class,
            VehicleResource::class,
            TripResource::class,
        ];
    }

    public function pages(): array
    {
        return [
            Dashboard::class,
            ResourceAvailability::class,
        ];
    }

    public function widgets(): array
    {
        return [
            CompletedTripsChart::class,
            TripStatusBreakdownChart::class,
            DriverAvailabilityChart::class,
            VehicleAvailabilityChart::class,
        ];
    }
};
