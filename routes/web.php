<?php

use Illuminate\Support\Facades\Route;

require base_path('app/Modules/Auth/Routes/web.php');
require base_path('app/Modules/Courses/Routes/web.php');
require base_path('app/Modules/StudentPortal/Routes/web.php');

Route::get('/', function () {
    return view('welcome');
});