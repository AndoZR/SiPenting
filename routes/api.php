<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\kalkulatorGiziController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\menuController;

Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register']);

Route::middleware(['auth:api', 'user:1'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('getuser', [AuthController::class, 'getUser']);

    Route::group(['prefix'=>'kalkulatorGizi'], function () {
        Route::get('/', [kalkulatorGiziController::class, 'getMakanan'])->name('getMakanan');
        Route::post('/cekGizi', [kalkulatorGiziController::class, 'cekGizi'])->name('cekGizi');
    });

});

Route::middleware(['auth:api', 'user:2'])->group(function () {
    Route::get('menu', [menuController::class, 'index'])->name('menu');
});



// Route::group(['middleware' => 'api','prefix' => 'auth'], function ($router) {
//     Route::post('refresh', [AuthController::class,'refresh']);
//     Route::post('me', [AuthController::class,'me']);
// });