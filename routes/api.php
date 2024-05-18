<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\authController;
use App\Http\Controllers\menuController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


// Route::post('/signin', [authController::class, 'signIn'])->name('signInPost');
// Route::get('logout', [authController::class, 'logout'])->name('logout');

Route::get('/menu', [menuController::class, 'index'])->name('menu');
Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class,'logout']);
});

Route::post('login', [AuthController::class,'login']);
Route::post('register', [AuthController::class,'register']);

Route::group(['middleware' => 'api','prefix' => 'auth'], function ($router) {
    Route::post('refresh', [AuthController::class,'refresh']);
    Route::post('me', [AuthController::class,'me']);
});