<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TotpController;

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

Route::middleware(['auth','2faroute'])->group(function () {
    //after Login
    Route::get('/authenticate',[TotpController::class,'index'])->name('authenticate');
    Route::post('/authenticate',[TotpController::class,'auth2fa']);

    //set 2FA first time
    Route::get('/enable2fa',[TotpController::class,'enable2fa'])->name('enable2fa');
    Route::post('/enable2fa',[TotpController::class,'activate2fa']);

});

Route::middleware(['auth','2fa'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

});

require __DIR__ . '/auth.php';
