<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('12345678'),
            'remember_token' => Str::random(10),
            'company_id' => Company::inRandomOrder()->first()->id ?? Company::factory(),
        ];
    }

    /**
     * State for an admin user.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'company_id' => null,
        ])->afterCreating(function (User $user) {
            $user->assignRole('admin');
        });
    }

    public function manager(): static
    {
        return $this->afterCreating(function (User $user) {
            $user->assignRole('manager');
        });
    }

    /**
     * State for a driver user.
     */
    public function driver(): static
    {
        return $this->afterCreating(function (User $user) {
            $user->assignRole('driver');
        });
    }
}
