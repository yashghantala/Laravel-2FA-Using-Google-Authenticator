<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::get('/', function () {
    return response()->json(auth()->user());
});

Route::middleware(['guest'])->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::middleware(['api2faroute'])->group(function () {
        Route::post('/authenticate', [AuthController::class, 'auth2fa'])->name('apiauthenticate');

        //set 2FA first time
        Route::get('/enable2fa', [AuthController::class, 'get2fa'])->name('apienable2fa');
        Route::post('/enable2fa', [AuthController::class, 'activate2fa']);
    });

    Route::middleware(['api2fa'])->group(function () {
        Route::get('/dashboard', function () {
            return response()->json('Api AUthenticated');
        });
    });
});
