<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $makes = ['Ford', 'Mercedes', 'Volvo', 'Scania', 'MAN'];
        $models = ['F-Max', 'Actros', 'FH16', 'R-Series', 'TGX'];
        $types = ['Truck', 'Van', 'Car'];

        return [
            'company_id' => Company::factory(),
            'make' => $this->faker->randomElement($makes),
            'model' => $this->faker->randomElement($models),
            'license_plate' => $this->faker->unique()->bothify('??######'),
            'year' => $this->faker->numberBetween(2010, 2023),
            'type' => $this->faker->randomElement($types),
        ];
    }
}
