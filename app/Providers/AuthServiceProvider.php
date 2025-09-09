<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

// Mevcut modeller/policy'ler
use App\Models\User;
use App\Policies\UserPolicy;
use Spatie\Permission\Models\Role;
use App\Policies\RolePolicy;
use App\Models\Service;
use App\Policies\ServicePolicy;
use App\Models\ServicePackage;
use App\Policies\ServicePackagePolicy;

// ✅ Yeni eklenenler
use App\Models\Customer;
use App\Policies\CustomerPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class           => UserPolicy::class,
        Role::class           => RolePolicy::class,
        Service::class        => ServicePolicy::class,
        ServicePackage::class => ServicePackagePolicy::class,
        Customer::class       => CustomerPolicy::class,   // ✅ eklendi
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // ✅ super_admin (guard=admin) için global bypass
        Gate::before(function ($user, $ability) {
            // Spatie Permission: hasRole($role, $guard = null)
            return $user?->hasRole('super_admin', 'admin') ? true : null;
        });
    }
}
