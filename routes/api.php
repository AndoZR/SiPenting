<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\bayiController;
use App\Http\Controllers\kalkulatorGiziController;
use App\Http\Controllers\kalkulatorStuntingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\menuController;
use App\Http\Controllers\posyanduController;

Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register']);

Route::middleware(['auth:api', 'role:1,2'])->group(function () {
    Route::get('getuser', [AuthController::class, 'getUser']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('updateProfile', [AuthController::class, 'updateProfile']);

    Route::group(['prefix'=>'posyandu'], function () {
        Route::get('/', [posyanduController::class, 'index'])->name('posyandu');
    });
});

Route::middleware(['auth:api', 'role:1'])->group(function () {
    Route::group(['prefix'=>'kalkulatorGizi'], function () {
        Route::get('/', [kalkulatorGiziController::class, 'getMakanan'])->name('getMakanan');
        Route::post('/cekGizi', [kalkulatorGiziController::class, 'cekGizi'])->name('cekGizi');
    });

    Route::group(['prefix'=>'kalkulatorStunting'], function () {
        Route::post('/cekStuntingIbu', [kalkulatorStuntingController::class, 'cekStuntingIbu'])->name('cekStuntingIbu');
        Route::post('/cekStuntingAnak', [kalkulatorStuntingController::class, 'cekStuntingAnak'])->name('cekStuntingAnak');
    });

    Route::group(['prefix'=>'bayi'], function () {
        Route::get('/', [bayiController::class, 'index'])->name('bayi');
        Route::post('/storeBayi', [bayiController::class, 'storeBayi'])->name('storeBayi');
        Route::post('/updateBayi', [bayiController::class, 'updateBayi'])->name('updateBayi');
        Route::post('/deleteBayi', [bayiController::class, 'deleteBayi'])->name('deleteBayi');
    });
});

Route::middleware(['auth:api', 'role:2'])->group(function () {
    Route::get('menu', [menuController::class, 'index'])->name('menu');

    Route::group(['prefix'=>'posyandu'], function () {
        Route::post('/posyByBidan', [posyanduController::class, 'posyByBidan'])->name('posyByBidan');
        Route::post('/storePosyandu', [posyanduController::class, 'storePosyandu'])->name('storePosyandu');
        Route::post('/updatePosyandu', [posyanduController::class, 'updatePosyandu'])->name('updatePosyandu');
        Route::post('/deletePosyandu', [posyanduController::class, 'deletePosyandu'])->name('deletePosyandu');
        Route::get('/jadwal', [posyanduController::class, 'getJadwal'])->name('getJadwal');
        Route::post('/storeJadwal', [posyanduController::class, 'storeJadwal'])->name('storeJadwal');
        Route::post('/updateJadwal', [posyanduController::class, 'updateJadwal'])->name('updateJadwal');
        Route::post('/deleteJadwal', [posyanduController::class, 'deleteJadwal'])->name('deleteJadwal');
    });
});


// Route::group(['middleware' => 'api','prefix' => 'auth'], function ($router) {
//     Route::post('refresh', [AuthController::class,'refresh']);
//     Route::post('me', [AuthController::class,'me']);
// });