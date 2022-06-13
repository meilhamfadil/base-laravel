<?php

namespace App\Providers;

use App\Models\Role;
use App\Models\ViewFeaturePermission;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

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

        $roles = Role::all();
        foreach ($roles as $role) {
            Gate::define('is' . $role->slug, function ($user) use ($role) {
                return $user->role_id == $role->id;
            });
        }

        $routes = ViewFeaturePermission::all();
        foreach ($routes as $route) {
            Gate::define($route->feature_slug, function ($user) use ($route) {
                $needed = preg_split('/\,/', $route->role_id);
                return in_array($user->role_id, $needed);
            });
        }
    }
}
