<?php

use App\Http\Controllers\Google2faController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    return view("welcome");
});

Route::middleware(['auth', 'g2fa'])->group(function () {

    //Google2fa Routes
    Route::get('/enable2fa', [Google2faController::class, 'enable'])->name('enable2fa');
    Route::post('/activate2fa', [Google2faController::class, 'activate'])->name('activate2fa');
    Route::post('/authenticate2fa', [Google2faController::class, 'auth2fa'])->name('authenticate2fa');

    //other routes
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/users', function () {
        return view('dashboard');
    })->name('users');
});

require __DIR__ . '/auth.php';
