<?php

namespace App\Providers;

use App\Domains\User\ACL\SystemRole;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
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

        // Platform admins have all permissions, always.
        Gate::before(function ($user, $ability) {
            return $user->hasRole(SystemRole::PLATFORM_ADMINISTRATOR) ? true : null;
        });
    }
}
