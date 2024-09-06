<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\artikelController;
use App\Http\Controllers\posyanduController;

// route ini untuk membaca file image langsung ke storage tanpa lewat public jadi di view make "{{ url('/storage/artikel') }}/" + fileName;
Route::get('/storage/artikel/{filename}', function ($filename) {
    $path = storage_path('app/public/artikel/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = response($file, 200)->header("Content-Type", $type);
    return $response;
});

Route::group(['prefix' => 'bidan'], function () {
    Route::get('/', [AuthController::class, 'viewRegisterBidan'])->name('viewRegisterBidan');
    Route::post('/register', [AuthController::class, 'registerBidan'])->name('registerBidan');
});

Route::group(['prefix' => 'artikel', 'as' => 'artikel.'], function () {
    Route::get('/viewArtikel', [artikelController::class, 'viewArtikel'])->name('viewArtikel');
    Route::post('/storeArtikel', [artikelController::class, 'storeArtikel'])->name('storeArtikel');
    Route::post('/updateArtikel/{id}', [artikelController::class, 'updateArtikel'])->name('updateArtikel');
    Route::get('/deleteArtikel/{id}', [artikelController::class, 'deleteArtikel'])->name('deleteArtikel');
});

Route::post('/save-subscription-id', [posyanduController::class, 'saveSubs']);

Route::get('/send-notification', [posyanduController::class, 'sendNotif']);

