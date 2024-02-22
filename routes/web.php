<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Agar User ketika sudah login tidak  bisa mengakses halaman utama lagi, jadi harus logout terlebih dahulu
Route::middleware(['guest'])->group(function () {
    Route::get('login', [AuthenController::class, 'login_view'])->name("login");
    Route::post('login', [AuthenController::class, 'proses_login']);
    Route::get('regis', [AuthenController::class, 'regis_view'])->name("regis");
    Route::post('regis', [AuthenController::class, 'proses_regis']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenController::class, 'proses_logout']);
    Route::get('home', function () {
        return view('home');
    });

    Route::get('admin', function () {
        return view('admin');
    });
});
