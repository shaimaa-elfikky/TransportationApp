<?php

namespace App\Providers;


use App\Models\Company;
use App\Models\Driver;
use App\Models\Trip;
use App\Models\User;
use App\Models\Vehicle;
use App\Policies\CompanyPolicy;
use App\Policies\DriverPolicy;
use App\Policies\TripPolicy;
use App\Policies\UserPolicy;
use App\Policies\VehiclePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
      protected $policies = [
        //  \Spatie\Permission\Models\Role::class => \App\Policies\RolePolicy::class,
        // \Spatie\Permission\Models\Permission::class => \App\Policies\PermissionPolicy::class,
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        User::class => UserPolicy::class,
        Company::class => CompanyPolicy::class,
        Driver::class => DriverPolicy::class,
        Vehicle::class => VehiclePolicy::class,
        Trip::class => TripPolicy::class,
    ];

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Gate::before(function (User $user, string $ability) {
            return $user->hasRole('admin') ? true : null;
        });
        
    }
}
