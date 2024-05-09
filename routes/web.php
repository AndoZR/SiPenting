<?php

use App\Http\Controllers\authController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [authController::class, 'index'])->name('signIn');
Route::post('/coba', [authController::class, 'coba'])->name('coba');
Route::post('/signin', [authController::class, 'signIn'])->name('signInPost');
Route::get('logout', [authController::class, 'logout'])->name('logout');