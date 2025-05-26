<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\admin\dashboard;
use App\Http\Controllers\artikelController;
use App\Http\Controllers\posyanduController;
use App\Http\Controllers\admin\dashboardController;

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

Route::get('/download/sipenting', function() {
    return view("downloadAPK");
});


Route::middleware(['guest:bidan,puskesmas,bapeda,dinkes'])->group(function() {
    Route::get('/', function() {
        return view("login");
    });
    Route::get('/login', [AuthController::class, 'loginWeb'])->name('login');
    Route::post('/login', [AuthController::class, 'webLoginProcess'])->name('login-web-process');
});

Route::middleware(['auth:bapeda','check'])->group(function() {
    Route::group(['prefix' => 'akun_puskesmas', 'as' => 'akun_puskesmas.'], function () {
        Route::get('/', [dashboardController::class, 'viewAkunPuskesmas'])->name('viewAkunPuskesmas');
        Route::post('/changePassword/{id}', [dashboardController::class, 'changePassword'])->name('changePassword');
    });
});


Route::middleware(['auth:bidan,puskesmas,bapeda','check'])->group(function() {
    Route::get('/home', [dashboardController::class, 'index'])->name('home');

    Route::group(['prefix' => 'artikel', 'as' => 'artikel.'], function () {
        Route::get('/', [artikelController::class, 'viewArtikel'])->name('viewArtikel');
        Route::post('/storeArtikel', [artikelController::class, 'storeArtikel'])->name('storeArtikel');
        Route::post('/updateArtikel/{id}', [artikelController::class, 'updateArtikel'])->name('updateArtikel');
        Route::get('/deleteArtikel/{id}', [artikelController::class, 'deleteArtikel'])->name('deleteArtikel');
    });

    Route::group(['prefix' => 'ibu-hamil', 'as' => 'ibu-hamil.'], function () {
        Route::get('/daftar', [dashboardController::class, 'daftar'])->name('ibu-hamil-daftar');
        Route::get('/graph/{id}', [dashboardController::class, 'graph'])->name('ibu-hamil-graph');
    });

    Route::group(['prefix' => 'anak', 'as' => 'anak.'], function () {
        Route::get('/daftar', [dashboardController::class, 'daftarAnak'])->name('daftar-anak');
        Route::get('/daftar/detail-gizi-anak/{id}', [dashboardController::class, 'detaildGiziAnak'])->name('detail-gizi-anak');
        Route::get('/daftar/detail-stunting-anak/{id}', [dashboardController::class, 'detaildStuntingAnak'])->name('detail-stunting-anak');
        
        Route::get('/giziKecamatan', [dashboardController::class, 'daftarKecamatanGizi'])->name('daftar-kecamatan-gizi');
        Route::get('/grafikGiziAnak/{id}', [dashboardController::class, 'graphGiziAnak'])->name('graph-gizi-anak');
        Route::get('/giziDesa/{id}', [dashboardController::class, 'daftarDesaGizi'])->name('daftar-desa-gizi');
        Route::get('/anak/export/village/{village_id}', [dashboardController::class, 'eksporExcel'])->name('anak.ekspor-excel');
        
        Route::get('/stuntingKecamatan', [dashboardController::class, 'daftarKecamatanStunting'])->name('daftar-kecamatan-stunting');
        Route::get('/grafikStuntingAnak/{id}', [dashboardController::class, 'graphStuntingAnak'])->name('graph-stunting-anak');
        Route::get('/giziStunting/{id}', [dashboardController::class, 'daftarDesaStunting'])->name('daftar-desa-stunting');
    });

    Route::get('/logout', [AuthController::class, 'logoutWeb'])->name('logout-web');
});



Route::group(['prefix' => 'bidan'], function () {
    Route::get('/', [AuthController::class, 'viewRegisterBidan'])->name('viewRegisterBidan');
    Route::post('/register', [AuthController::class, 'registerBidan'])->name('registerBidan');
});



Route::post('/save-subscription-id', [posyanduController::class, 'saveSubs']);


// for artisan:
// Route::get('/run-migrate', function () {
//     try {
//         Artisan::call('migrate:fresh --seed');
//         return response()->json(['message' => 'Migrasi dan seed telah dijalankan.']);
//     } catch (\Exception $e) {
//         return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
//     }
// });