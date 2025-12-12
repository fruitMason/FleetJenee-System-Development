<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //Gate
        Gate::define('is-fleet-manager', function ($user) {
            return $user->hasRole('FLEET MANAGER');
        });

        Gate::define('is-driver', function ($user) {
            return $user->hasRole('DRIVER');
        });
        Gate::define('is-mechanic', function ($user) {
            return $user->hasRole('MECHANIC');
        });
        Gate::define('is-finance', function ($user) {
            return $user->hasRole('FINANCE');
        });


        Gate::define('is-fleet-manager', function ($user) {
            return $user->hasRole('FLEET MANAGER');
        });
    }
}
