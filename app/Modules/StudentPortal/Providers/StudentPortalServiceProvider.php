<?php

namespace App\Modules\StudentPortal\Providers;

use Illuminate\Support\ServiceProvider;

class StudentPortalServiceProvider extends ServiceProvider
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
