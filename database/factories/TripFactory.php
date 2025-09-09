<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Driver;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trip>
 */
class TripFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('-1 month', '+1 month');
        $end = (clone $start)->modify('+'.$this->faker->numberBetween(2, 24).' hours');

        return [
            'company_id' => Company::factory(),
            'driver_id' => Driver::factory(),
            'vehicle_id' => Vehicle::factory(),
            'name' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'origin' => $this->faker->city().', '.$this->faker->countryCode(),
            'destination' => $this->faker->city().', '.$this->faker->countryCode(),
            'start_time' => $start,
            'end_time' => $end,
            'status' => $this->faker->randomElement([
                'scheduled',
                'ongoing',
                'completed',
                'cancelled',
            ]),
        ];
    }

    public function scheduled(): Factory
    {
        return $this->state(function (array $attributes) {
            $start = $this->faker->dateTimeBetween('now', '+1 month');
            $end = (clone $start)->modify('+'.$this->faker->numberBetween(2, 24).' hours');

            return [
                'start_time' => $start,
                'end_time' => $end,
                'status' => 'scheduled',
            ];
        });
    }

    public function ongoing(): Factory
    {
        return $this->state(function (array $attributes) {
            $start = $this->faker->dateTimeBetween('-1 day', 'now');
            $end = (clone $start)->modify('+'.$this->faker->numberBetween(2, 24).' hours');

            return [
                'start_time' => $start,
                'end_time' => $end,
                'status' => 'ongoing',
            ];
        });
    }

    public function completed(): Factory
    {
        return $this->state(function (array $attributes) {
            $start = $this->faker->dateTimeBetween('-2 months', '-1 month');
            $end = (clone $start)->modify('+'.$this->faker->numberBetween(2, 24).' hours');

            return [
                'start_time' => $start,
                'end_time' => $end,
                'status' => 'completed',
            ];
        });
    }
}
