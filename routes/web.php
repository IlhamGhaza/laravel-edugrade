<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Homepage menampilkan landing page EduGrade.
| Semua operasi aplikasi dilakukan melalui Filament panel di /admin.
|
*/

Route::get('/', function () {
    return view('welcome');
});
