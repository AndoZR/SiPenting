<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\authController;
use App\Http\Controllers\menuController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::post('/signin', [authController::class, 'signIn'])->name('signInPost');
Route::get('logout', [authController::class, 'logout'])->name('logout');

Route::group(['middleware' => 'user'], function () {
Route::get('/menu', [menuController::class, 'index'])->name('menu');
});