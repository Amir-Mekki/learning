<?php
use Illuminate\Support\Facades\Route;
use App\Modules\Auth\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::get('/callback', [AuthController::class, 'callback'])->name('callback');
