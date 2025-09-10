<?php

use App\Enums\TripStatus;
use App\Models\Driver;
use App\Models\Trip;
use App\Models\Vehicle;
use App\Rules\ResourceIsAvailable;
use Illuminate\Support\Facades\Validator;

it('driver is available when no overlapping trips', function () {
    $driver = Driver::factory()->create();
    // A non-overlapping trip (yesterday)
    Trip::factory()->create([
        'driver_id' => $driver->id,
        'vehicle_id' => Vehicle::factory(),
        'start_time' => now()->subDay()->setTime(8, 0),
        'end_time' => now()->subDay()->setTime(12, 0),
        // status intentionally omitted; availability ignores status
    ]);

    expect($driver->isAvailable(
        now()->setTime(10, 0)->toDateTimeString(),
        now()->setTime(12, 0)->toDateTimeString()
    ))->toBeTrue();
});

it('driver is not available when overlapping trips exist', function () {
    $driver = Driver::factory()->create();
    $vehicle = Vehicle::factory()->create();

    Trip::factory()->create([
        'driver_id' => $driver->id,
        'vehicle_id' => $vehicle->id,
        'start_time' => now()->setTime(9, 0),
        'end_time' => now()->setTime(13, 0),
        // status intentionally omitted
    ]);

    expect($driver->isAvailable(
        now()->setTime(10, 0)->toDateTimeString(),
        now()->setTime(12, 0)->toDateTimeString()
    ))->toBeFalse();
});

it('vehicle is available when no overlapping trips', function () {
    $vehicle = Vehicle::factory()->create();
    Trip::factory()->create([
        'driver_id' => Driver::factory(),
        'vehicle_id' => $vehicle->id,
        'start_time' => now()->subDay()->setTime(8, 0),
        'end_time' => now()->subDay()->setTime(12, 0),
        // status intentionally omitted
    ]);

    expect($vehicle->isAvailable(
        now()->setTime(10, 0)->toDateTimeString(),
        now()->setTime(12, 0)->toDateTimeString()
    ))->toBeTrue();
});

it('vehicle is not available when overlapping trips exist', function () {
    $vehicle = Vehicle::factory()->create();
    $driver = Driver::factory()->create();

    Trip::factory()->create([
        'driver_id' => $driver->id,
        'vehicle_id' => $vehicle->id,
        'start_time' => now()->setTime(9, 0),
        'end_time' => now()->setTime(13, 0),
        // status intentionally omitted
    ]);

    expect($vehicle->isAvailable(
        now()->setTime(10, 0)->toDateTimeString(),
        now()->setTime(12, 0)->toDateTimeString()
    ))->toBeFalse();
});

it('validation rule passes when resource is available', function () {
    $driver = Driver::factory()->create();
    $startTime = now()->setTime(10, 0)->toDateTimeString();
    $endTime = now()->setTime(12, 0)->toDateTimeString();

    $validator = Validator::make([
        'driver_id' => $driver->id,
        'start_time' => $startTime,
        'end_time' => $endTime,
    ], [
        'driver_id' => [new ResourceIsAvailable('driver')],
    ]);

    expect($validator->fails())->toBeFalse();
});

it('validation rule fails when resource is not available', function () {
    $driver = Driver::factory()->create();
    $vehicle = Vehicle::factory()->create();

    $tripStart = '2024-01-01 09:00:00';
    $tripEnd = '2024-01-01 13:00:00';
    $checkStart = '2024-01-01 10:00:00';
    $checkEnd = '2024-01-01 12:00:00';

    Trip::factory()->create([
        'driver_id' => $driver->id,
        'vehicle_id' => $vehicle->id,
        'start_time' => $tripStart,
        'end_time' => $tripEnd,
        'status' => TripStatus::Scheduled->value,
    ]);

    // First, let's test the isAvailable method directly to confirm model logic
    $isAvailable = $driver->isAvailable($checkStart, $checkEnd);
    expect($isAvailable)->toBeFalse('Driver should not be available for overlapping time');

    // Test the validation rule by simulating request data
    $validator = Validator::make([
        'driver_id' => $driver->id,
        'start_time' => $checkStart,
        'end_time' => $checkEnd,
    ], [
        'driver_id' => [new ResourceIsAvailable('driver')],
    ]);

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->first('driver_id'))->toContain('not available');
});
