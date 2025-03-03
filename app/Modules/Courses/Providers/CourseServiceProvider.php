<?php

namespace App\Modules\Courses\Providers;

use Illuminate\Support\ServiceProvider;
use App\Modules\Courses\Models\Enrollment;
use App\Modules\Courses\Observers\EnrollmentObserver;

class CourseServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/migrations');
        Enrollment::observe(EnrollmentObserver::class);
    }

    public function register()
    {
        //
    }
}
