<?php
use Illuminate\Support\Facades\Route;
use App\Modules\Courses\Controllers\DepartmentController;
use App\Modules\Courses\Controllers\CourseController;
use App\Modules\Courses\Controllers\LessonController;
use App\Modules\Courses\Controllers\ResourceController;

Route::middleware(['auth:api'])->group(function () {
    // Routes pour les départements
    Route::prefix('departments')->group(function () {
        Route::get('/', [DepartmentController::class, 'index'])
            ->name('departments.index')
            ->middleware('EnsureHasRole:admin');

        Route::post('/', [DepartmentController::class, 'store'])
            ->name('departments.store')
            ->middleware('EnsureHasRole:admin');

        Route::get('/{id}', [DepartmentController::class, 'show'])
            ->name('departments.show')
            ->middleware('EnsureHasRole:admin');

        Route::put('/{id}', [DepartmentController::class, 'update'])
            ->name('departments.update')
            ->middleware('EnsureHasRole:admin');

        Route::delete('/{id}', [DepartmentController::class, 'destroy'])
            ->name('departments.destroy')
            ->middleware('EnsureHasRole:admin');
    });

    // Routes pour les cours
    Route::prefix('courses')->group(function () {
        Route::get('/', [CourseController::class, 'index'])
            ->name('courses.index')
            ->middleware('EnsureHasRole:student,teacher,department_head,admin');

        Route::post('/', [CourseController::class, 'store'])
            ->name('courses.store')
            ->middleware('EnsureHasRole:department_head,admin');

        Route::get('/{id}', [CourseController::class, 'show'])
            ->name('courses.show')
            ->middleware('EnsureHasRole:student,teacher,department_head,admin');

        Route::put('/{id}', [CourseController::class, 'update'])
            ->name('courses.update')
            ->middleware('EnsureHasRole:department_head,admin');

        Route::delete('/{id}', [CourseController::class, 'destroy'])
            ->name('courses.destroy')
            ->middleware('EnsureHasRole:department_head,admin');
    });

    // Routes pour les leçons
    Route::middleware(['auth:api'])->prefix('lessons')->group(function () {
        Route::get('/', [LessonController::class, 'index'])->name('lessons.index')
            ->middleware('EnsureHasRole:teacher,student,admin');

        Route::post('/', [LessonController::class, 'store'])->name('lessons.store')
            ->middleware('EnsureHasRole:teacher,admin');

        Route::get('/{id}', [LessonController::class, 'show'])->name('lessons.show')
            ->middleware('EnsureHasRole:teacher,student,admin');

        Route::put('/{id}', [LessonController::class, 'update'])->name('lessons.update')
            ->middleware('EnsureHasRole:teacher,admin');

        Route::delete('/{id}', [LessonController::class, 'destroy'])->name('lessons.destroy')
            ->middleware('EnsureHasRole:admin');
    });

    // Routes pour les ressources
    Route::middleware(['auth:api'])->prefix('resources')->group(function () {
        Route::get('/', [ResourceController::class, 'index'])->name('resources.index')
            ->middleware('EnsureHasRole:teacher,student,admin');

        Route::post('/', [ResourceController::class, 'store'])->name('resources.store')
            ->middleware('EnsureHasRole:teacher,admin');

        Route::get('/{id}', [ResourceController::class, 'show'])->name('resources.show')
            ->middleware('EnsureHasRole:teacher,student,admin');

        Route::put('/{id}', [ResourceController::class, 'update'])->name('resources.update')
            ->middleware('EnsureHasRole:teacher,admin');

        Route::delete('/{id}', [ResourceController::class, 'destroy'])->name('resources.destroy')
            ->middleware('EnsureHasRole:admin');
    });

    
    // Routes pour les inscriptions
    Route::middleware(['auth:api'])->prefix('enrollments')->group(function () {
        Route::get('/', [EnrollmentController::class, 'index'])->name('enrollments.index')
            ->middleware('EnsureHasRole:student,department_head,admin');

        Route::post('/', [EnrollmentController::class, 'store'])->name('enrollments.store')
            ->middleware('EnsureHasRole:student,department_head,admin');

        Route::put('/{id}', [EnrollmentController::class, 'update'])->name('enrollments.update')
            ->middleware('EnsureHasRole:department_head,admin');

        Route::delete('/{id}', [EnrollmentController::class, 'destroy'])->name('enrollments.destroy')
            ->middleware('EnsureHasRole:student,department_head,admin');
    });
});
