<?php

namespace Tests\Unit;

use App\Models\Driver;
use App\Models\Vehicle;
use App\Rules\ResourceIsAvailable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class ResourceIsAvailableTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup test data for drivers and vehicles
        Driver::factory()->create(['id' => 1]);
        Vehicle::factory()->create(['id' => 1]);
    }

    public function test_driver_is_available()
    {
        $rule = (new ResourceIsAvailable('driver'))->setData([
            'start_time' => '2024-01-01 10:00:00',
            'end_time' => '2024-01-01 12:00:00',
        ]);

        $validator = Validator::make(
            ['driver_id' => 1],
            ['driver_id' => [$rule]]
        );

        $this->assertFalse($validator->fails());
    }

    public function test_vehicle_is_available()
    {
        $rule = (new ResourceIsAvailable('vehicle'))->setData([
            'start_time' => '2024-01-01 10:00:00',
            'end_time' => '2024-01-01 12:00:00',
        ]);

        $validator = Validator::make(
            ['vehicle_id' => 1],
            ['vehicle_id' => [$rule]]
        );

        $this->assertFalse($validator->fails());
    }

    public function test_driver_is_not_available()
    {
        // Mock the Driver model's isAvailable method to return false
        $this->mock(Driver::class, function ($mock) {
            $mock->shouldReceive('find')->with(1)->andReturnSelf();
            $mock->shouldReceive('isAvailable')->andReturn(false);
        });

        $rule = (new ResourceIsAvailable('driver'))->setData([
            'start_time' => '2024-01-01 10:00:00',
            'end_time' => '2024-01-01 12:00:00',
        ]);

        $validator = Validator::make(
            ['driver_id' => 1],
            ['driver_id' => [$rule]]
        );

        $this->assertTrue($validator->fails());
        $this->assertEquals(
            'The selected driver is not available for the chosen time frame.',
            $validator->errors()->first('driver_id')
        );
    }

    public function test_vehicle_is_not_available()
    {
        // Mock the Vehicle model's isAvailable method to return false
        $this->mock(Vehicle::class, function ($mock) {
            $mock->shouldReceive('find')->with(1)->andReturnSelf();
            $mock->shouldReceive('isAvailable')->andReturn(false);
        });

        $rule = (new ResourceIsAvailable('vehicle'))->setData([
            'start_time' => '2024-01-01 10:00:00',
            'end_time' => '2024-01-01 12:00:00',
        ]);

        $validator = Validator::make(
            ['vehicle_id' => 1],
            ['vehicle_id' => [$rule]]
        );

        $this->assertTrue($validator->fails());
        $this->assertEquals(
            'The selected vehicle is not available for the chosen time frame.',
            $validator->errors()->first('vehicle_id')
        );
    }
}
