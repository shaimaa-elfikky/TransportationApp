<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    public function up(): void
    {

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $this->createAdminAndSyncPermissions();
        $this->createManagerAndSyncPermissions();
        $this->createDriverAndSyncPermissions();

    }

    public function createAdminAndSyncPermissions(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $permissions = Permission::all();
        $adminRole->syncPermissions($permissions);
    }

    public function createManagerAndSyncPermissions(): void
    {
        $role = self::roleModel()::firstOrCreate([
            'name' => 'Manager',
            'guard_name' => 'web',
        ]);

        $permissions = self::permissionModel()::query()
            ->where('guard_name', 'web')
            ->whereIn('name', [
                'page_ResourceAvailability',
                // Dashboard Widgets
                'widget_CompletedTripsChart', 'widget_TripStatusBreakdownChart', 'widget_DriverAvailabilityChart', 'widget_VehicleAvailabilityChart',

                'page_ReportsPage', 'page_POS',

                // CompanyResource
                'view_company', 'view_any_company', 'create_company', 'update_company',

                // DriverResource
                'view_driver', 'view_any_driver', 'create_driver', 'update_driver', 'delete_driver', 'delete_any_driver',

                // TripResource
                'view_trip', 'view_any_trip', 'create_trip', 'update_trip', 'delete_trip', 'delete_any_trip',

                // VehicleResource
                'view_vehicle', 'view_any_vehicle', 'create_vehicle', 'update_vehicle', 'delete_vehicle', 'delete_any_vehicle',
            ])
            ->pluck('name');

        $role->syncPermissions($permissions);
    }

    public function createDriverAndSyncPermissions(): void
    {
        $driverRole = Role::firstOrCreate(['name' => 'driver']);
        $permissions = Permission::whereIn('name', [
            'view_trips',
            'view_any_trip',
        ])->get();
        $driverRole->syncPermissions($permissions);
    }

    public function down(): void
    {
        Role::whereIn('name', ['admin', 'manager', 'driver'])->delete();
    }

    public static function roleModel(): string
    {
        return config('permission.models.role');
    }

    public static function permissionModel(): string
    {
        return config('permission.models.permission');
    }
};
