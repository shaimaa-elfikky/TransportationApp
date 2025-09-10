<?php

namespace App\Filament\Widgets;

use App\Enums\TripStatus;
use App\Models\Trip;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class CompletedTripsChart extends ChartWidget
{
    protected static ?string $heading = 'Completed Trips Over Time';
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $data = [];
        $labels = [];

     
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $month = $date->format('M'); 
            $year = $date->format('Y');

          
            $count = Trip::query()
                ->where('status', TripStatus::Completed)
                ->whereMonth('updated_at', $date->month)
                ->whereYear('updated_at', $date->year)
                ->count();

            $data[] = $count;
            $labels[] = $month;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Trips Completed',
                    'data' => $data,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}