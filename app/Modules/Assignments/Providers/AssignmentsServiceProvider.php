<?php

namespace App\Modules\Assignments\Providers;

use Illuminate\Support\ServiceProvider;

class AssignmentsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/migrations');
    }

    public function register()
    {
        //
    }
}
