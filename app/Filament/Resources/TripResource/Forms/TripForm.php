<?php

namespace App\Filament\Resources\TripResource\Forms;

use App\Enums\TripStatus;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Rules\ResourceIsAvailable;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Illuminate\Database\Eloquent\Builder;

class TripForm
{
    public static function getSchema(): array
    {
        return [
            TextInput::make('origin')
                ->required()
                ->maxLength(255)
                ->live(onBlur: true),

            TextInput::make('destination')
                ->required()
                ->maxLength(255)
                ->live(onBlur: true),

            DateTimePicker::make('start_time')
                ->label('scheduled start at')
                ->required()
                ->live()
                ->disabled(fn (Get $get) => ! $get('origin') || ! $get('destination')),

            DateTimePicker::make('end_time')
                ->label('scheduled end at')
                ->required()
                ->live()
                ->disabled(fn (Get $get) => ! $get('start_time'))
                ->afterOrEqual('start_time'),

            Select::make('vehicle_id')
                ->label('Vehicle')
                ->options(function (Get $get, $livewire) {
                    $startTime = $get('start_time');
                    $endTime = $get('end_time');
                    $tripIdBeingEdited = $livewire->record?->id;

                    if (! $startTime || ! $endTime) {
                        return Vehicle::query()->limit(0)->pluck('make', 'id');
                    }

                    return Vehicle::query()
                        ->whereDoesntHave('trips', function (Builder $query) use ($startTime, $endTime, $tripIdBeingEdited) {
                            $query
                                ->where('start_time', '<', $endTime)
                                ->where('end_time', '>', $startTime)
                                ->when($tripIdBeingEdited, fn ($q) => $q->where('id', '!=', $tripIdBeingEdited));
                        })
                        ->pluck('make', 'id');
                })
                ->searchable()
                ->preload()
                ->required()
                ->disabled(fn (Get $get) => ! $get('start_time') || ! $get('end_time'))
                ->rules([new ResourceIsAvailable('vehicle')]),

            Select::make('driver_id')
                ->label('Driver')
                ->options(function (Get $get, $livewire) {
                    $startTime = $get('start_time');
                    $endTime = $get('end_time');
                    $tripIdBeingEdited = $livewire->record?->id;

                    if (! $startTime || ! $endTime) {
                        return Driver::query()->limit(0)->pluck('name', 'id');
                    }

                    return Driver::query()
                        ->whereDoesntHave('trips', function (Builder $query) use ($startTime, $endTime, $tripIdBeingEdited) {
                            $query
                                ->where('start_time', '<', $endTime)
                                ->where('end_time', '>', $startTime)
                                ->when($tripIdBeingEdited, fn ($q) => $q->where('id', '!=', $tripIdBeingEdited));
                        })
                        ->pluck('name', 'id');
                })
                ->searchable()
                ->preload()
                ->required()
                ->disabled(fn (Get $get) => ! $get('start_time') || ! $get('end_time'))
                ->rules([new ResourceIsAvailable('driver')]),

            Select::make('status')
                ->options(TripStatus::class)
                ->required()
                ->default(TripStatus::Scheduled),
        ];
    }
}
