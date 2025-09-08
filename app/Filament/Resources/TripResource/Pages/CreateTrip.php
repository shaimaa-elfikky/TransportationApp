<?php

namespace App\Filament\Resources\TripResource\Pages;

use App\Filament\Resources\TripResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateTrip extends CreateRecord
{
    protected static string $resource = TripResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $driver = \App\Models\Driver::find($data['driver_id']);
        $vehicle = \App\Models\Vehicle::find($data['vehicle_id']);

        if (!$driver->isAvailable($data['start_time'], $data['end_time'])) {
            Notification::make()
                ->title('Driver Not Available')
                ->danger()
                ->body('The selected driver is already booked for an overlapping trip.')
                ->send();
            $this->halt();
        }

        if (!$vehicle->isAvailable($data['start_time'], $data['end_time'])) {
            Notification::make()
                ->title('Vehicle Not Available')
                ->danger()
                ->body('The selected vehicle is already booked for an overlapping trip.')
                ->send();
            $this->halt();
        }

        return $data;
    }
}