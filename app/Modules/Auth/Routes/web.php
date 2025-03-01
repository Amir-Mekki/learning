<?php
use Illuminate\Support\Facades\Route;
use App\Modules\Auth\Controllers\KeycloakAuthController;

Route::get('/login', [KeycloakAuthController::class, 'login'])->name('login');
Route::get('/callback', [KeycloakAuthController::class, 'callback'])->name('keycloak.callback');
