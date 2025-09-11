<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration {
    public function up(): void
    {

        app()[PermissionRegistrar::class]->forgetCachedPermissions();


        $allPermissions = Permission::pluck('name')->all();

        // --- ADMIN ---
        Role::create(['name' => 'admin'])->givePermissionTo($allPermissions);

        // --- MANAGER ---
        $managerExclusions = ['delete_company'];

        $managerPermissions = collect($allPermissions)->filter(function ($permission) use ($managerExclusions) {
            return !in_array($permission, $managerExclusions);
        });

        Role::create(['name' => 'manager'])->givePermissionTo($managerPermissions);


        // --- DRIVER ---
        $driverPermissions = [
            'view_trip',
        ];

        Role::create(['name' => 'driver'])->givePermissionTo($driverPermissions);
    }

    public function down(): void
    {
        Role::whereIn('name', ['admin', 'manager', 'driver'])->delete();
    }
};