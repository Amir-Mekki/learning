<?php
use Illuminate\Support\Facades\Route;
use App\Modules\Courses\Controllers\CourseController;

Route::middleware(['auth'])->group(function () {
    Route::prefix('courses')->group(function () {
        Route::get('/', [CourseController::class, 'index'])
            ->name('courses.index')
            ->middleware('keycloak-web-can-one:teacher|student|admin');

        Route::get('/create', [CourseController::class, 'create'])
            ->name('courses.create')
            ->middleware('keycloak-web-can-one:teacher|admin');

        Route::post('/', [CourseController::class, 'store'])
            ->name('courses.store')
            ->middleware('keycloak-web-can-one:teacher|admin');

        Route::get('/{id}', [CourseController::class, 'show'])
            ->name('courses.show')
            ->middleware('keycloak-web-can-one:teacher|student|admin');

        Route::get('/{id}/edit', [CourseController::class, 'edit'])
            ->name('courses.edit')
            ->middleware('keycloak-web-can-one:teacher|admin');

        Route::put('/{id}', [CourseController::class, 'update'])
            ->name('courses.update')
            ->middleware('keycloak-web-can-one:teacher|admin');

        Route::delete('/{id}', [CourseController::class, 'destroy'])
            ->name('courses.destroy')
            ->middleware('keycloak-web-can-one:admin');
    });
});
