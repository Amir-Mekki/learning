<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

require base_path('app/Modules/Auth/Routes/web.php');
require base_path('app/Modules/Courses/Routes/web.php');

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return 'Dashboard';
    });

    // Group routes for Admin
    Route::middleware(['keycloak-web-can:admin'])->prefix('admin')->group(function () {
        Route::get('/', function () {
            return 'Admin Dashboard';
        });
        Route::get('/settings', function () {
            return 'Admin Settings';
        });
    });

    // Group routes for Teacher
    Route::middleware(['keycloak-web-can:teacher'])->prefix('teacher')->group(function () {
        Route::get('/', function () {
            return 'Teacher Dashboard';
        });
    });

});
