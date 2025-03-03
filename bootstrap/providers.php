<?php

return [
    App\Providers\AppServiceProvider::class,
    \KeycloakGuard\KeycloakGuardServiceProvider::class,
    App\Modules\Courses\Providers\CourseServiceProvider::class,
];
