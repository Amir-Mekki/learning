<?php
use Illuminate\Support\Facades\Route;
use App\Modules\StudentPortal\Controllers\ProfileController;
use App\Modules\StudentPortal\Controllers\AchievementController;
use App\Modules\StudentPortal\Controllers\PerformanceController;

Route::middleware(['auth:api'])->group(function () {
    // Routes pour le profile
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])
            ->name('profile.show')
            ->middleware('EnsureHasRole:student');

        Route::put('/edit-account', [ProfileController::class, 'update'])
            ->name('profile.update')
            ->middleware('EnsureHasRole:student');
    });

    Route::get('/progress', [ProgressController::class, 'index'])
        ->name('student.progress')
        ->middleware('EnsureHasRole:student');

    Route::get('/achievements', [AchievementController::class, 'index'])
        ->name('student.achievements')
        ->middleware('EnsureHasRole:student');

    Route::get('/performance', [PerformanceController::class, 'index'])
        ->name('student.performance')
        ->middleware('EnsureHasRole:student');
});
