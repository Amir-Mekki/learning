<?php
use Illuminate\Support\Facades\Route;
use App\Modules\Assignments\Controllers\AssignmentController;

Route::middleware(['auth:api'])->group(function () {
     // Routes pour les devoirs
    Route::prefix('assignments')->group(function () {
        Route::get('/', [AssignmentController::class, 'index'])
            ->name('assignments.index')
            ->middleware('EnsureHasRole:teacher,student');

        Route::post('/', [AssignmentController::class, 'store'])
            ->name('assignments.store')
            ->middleware('EnsureHasRole:teacher');

        Route::get('/{id}', [AssignmentController::class, 'show'])
            ->name('assignments.show')
            ->middleware('EnsureHasRole:student,teacher');

        Route::put('/{id}', [AssignmentController::class, 'update'])
            ->name('assignments.update')
            ->middleware('EnsureHasRole:teacher');

        Route::delete('/{id}', [AssignmentController::class, 'destroy'])
            ->name('assignments.destroy')
            ->middleware('EnsureHasRole:teacher');

        Route::post('/{assignmentId}/submit', [AssignmentController::class, 'submit'])
            ->name('assignments.submit')
            ->middleware('EnsureHasRole:student');

        Route::update('/{submissionId}/grade', [AssignmentController::class, 'grade'])
            ->name('assignments.grade')
            ->middleware('EnsureHasRole:teacher');
    });
});
