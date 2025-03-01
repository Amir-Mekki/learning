<?php

namespace App\Modules\Auth\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any authentication services.
     */
    public function boot(): void
    {
        Gate::define('access-admin', function ($user) {
            return $user->hasRole('admin');
        });

        Gate::define('access-teacher', function ($user) {
            return $user->hasRole('teacher');
        });

        Gate::define('access-department-head', function ($user) {
            return $user->hasRole('department_head');
        });

        Gate::define('access-student', function ($user) {
            return $user->hasRole('student');
        });

        Gate::define('access-guest', function ($user) {
            return $user->hasRole('guest');
        });
    }
}
