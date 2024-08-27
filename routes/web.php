<?php

use App\Http\Controllers\artikelController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'bidan'], function () {
    Route::get('/', [AuthController::class, 'viewRegisterBidan'])->name('viewRegisterBidan');
    Route::post('/register', [AuthController::class, 'registerBidan'])->name('registerBidan');
});

Route::group(['prefix' => 'artikel', 'as' => 'artikel.'], function () {
    Route::get('/', [artikelController::class, 'index'])->name('index');
    Route::post('/storeArtikel', [artikelController::class, 'storeArtikel'])->name('storeArtikel');
    Route::post('/updateArtikel/{id}', [artikelController::class, 'updateArtikel'])->name('updateArtikel');
    Route::get('/deleteArtikel/{id}', [artikelController::class, 'deleteArtikel'])->name('deleteArtikel');
});
