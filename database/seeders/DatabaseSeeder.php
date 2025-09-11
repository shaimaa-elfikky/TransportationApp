<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Driver;
use App\Models\Trip;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $companies = Company::factory(3)->create();

        $admin = User::where('email', 'admin@admin.com')->first();
        if (!$admin) {
            $admin = User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@admin.com',
                'password' => Hash::make('12345678'),
                'company_id' => $companies->first()->id,
            ]);
        }
        $admin->assignRole('admin');

        $manager = User::where('email', 'manager@manager.com')->first();
        if (!$manager) {
            $manager = User::factory()->create([
                'name' => 'Manager User',
                'email' => 'manager@manager.com',
                'password' => Hash::make('12345678'),
                'company_id' => $companies->first()->id,
            ]);
        }
        $manager->assignRole('manager');

        foreach ($companies as $company) {
            // Create 10 drivers per company
            $drivers = Driver::factory(10)
                ->for($company)
                ->create();

            // Create 15 vehicles per company
            $vehicles = Vehicle::factory(15)
                ->for($company)
                ->create();

            // Create 50 trips per company
            Trip::factory(50)
                ->for($company)
                ->sequence(
                    fn ($sequence) => [
                        'driver_id' => $drivers->random()->id,
                        'vehicle_id' => $vehicles->random()->id,
                    ],
                )
                ->create();

            // Create some ongoing trips for immediate display
            Trip::factory(5)
                ->for($company)
                ->ongoing()
                ->sequence(
                    fn ($sequence) => [
                        'driver_id' => $drivers->random()->id,
                        'vehicle_id' => $vehicles->random()->id,
                    ],
                )
                ->create();

            // Create some scheduled trips
            Trip::factory(10)
                ->for($company)
                ->scheduled()
                ->sequence(
                    fn ($sequence) => [
                        'driver_id' => $drivers->random()->id,
                        'vehicle_id' => $vehicles->random()->id,
                    ],
                )
                ->create();

            // Create some completed trips
            Trip::factory(20)
                ->for($company)
                ->completed()
                ->sequence(
                    fn ($sequence) => [
                        'driver_id' => $drivers->random()->id,
                        'vehicle_id' => $vehicles->random()->id,
                    ],
                )
                ->create();
        }
    }
}
