<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\authController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/', [authController::class, 'index'])->name('signIn');
Route::post('coba', [authController::class, 'coba'])->name('coba');
Route::post('/signin', [authController::class, 'signIn'])->name('signInPost');
Route::get('logout', [authController::class, 'logout'])->name('logout');