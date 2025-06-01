<?php

use App\Models\villages;
use App\Models\akun_puskesmas;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\bayiController;
use App\Http\Controllers\menuController;
use App\Http\Controllers\artikelController;
use App\Http\Controllers\posyanduController;
use App\Http\Controllers\kalkulatorGiziController;
use App\Http\Controllers\kalkulatorStuntingController;
use App\Models\pivot_puskesmas_village;

Route::get('/kontak/', function() {
    $idVillage = Auth::user()->id_villages;

    if (!$idVillage) {
        return response()->json(['message' => 'ID desa tidak ditemukan'], 400);
    }

    // Ambil data desa, termasuk kecamatannya
    $desa = villages::with('district')->where('id', $idVillage)->first();

    if (!$desa || !$desa->district) {
        return response()->json(['message' => 'Desa atau kecamatan tidak ditemukan'], 404);
    }

    $puskesmas = null;

    if ($desa->district->id == '3511100') {
        // Kecamatan Bondowoso → ambil dari pivot relasi puskesmas_village
        $puskesmas = akun_puskesmas::whereHas('villages', function ($query) use ($idVillage) {
            $query->where('villages.id', $idVillage);
        })->first();

    } else {
        // Selain Kecamatan Bondowoso → ambil dari kolom id_district
        $puskesmas = akun_puskesmas::where('id_district', $desa->district->id)->first();
    }


    if (!$puskesmas || !$puskesmas->nomor) {
        return response()->json(['message' => 'Nomor WA tidak ditemukan'], 404);
    }

    $message = urlencode("Halo Pak/Ibu " . $puskesmas->name . ", Saya dari pengguna Aplikasi Sipenting ingin berkonsultasi.");

    // Format nomor WA: ubah 08xxx menjadi 628xxx
    $noWa = $puskesmas->nomor;
    $noWa = preg_replace('/^0/', '62', $noWa); // ganti 0 di awal jadi 62

    $waLink = "https://wa.me/" . $noWa . "?text=" . $message;

    return ResponseFormatter::success($waLink,"Berhasil Mendapatkan Link Whatsapp!");
});

Route::middleware(['guest'])->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::get('kecamatan', [AuthController::class, 'getKecamatan']);
    Route::post('desa', [AuthController::class, 'getDesa']);
});

Route::middleware(['auth:api', 'role:1,2'])->group(function () {
    Route::get('getuser', [AuthController::class, 'getUser']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('updateProfile', [AuthController::class, 'updateProfile']);
    Route::post('getIdSubs', [AuthController::class, 'getIdSubs']);

    Route::group(['prefix'=>'posyandu'], function () {
        Route::get('/', [posyanduController::class, 'index'])->name('posyandu');
        Route::post('/jadwal', [posyanduController::class, 'getJadwal'])->name('getJadwal');
    });
});



Route::middleware(['auth:api', 'role:1,3'])->group(function () {
    Route::group(['prefix'=>'kalkulatorGizi'], function () {
        Route::get('/', [kalkulatorGiziController::class, 'getMakanan'])->name('getMakanan');
        Route::post('/cekGizi', [kalkulatorGiziController::class, 'cekGizi'])->name('cekGizi');
        Route::post('/cekGiziGuest', [kalkulatorGiziController::class, 'cekGiziGuest'])->name('cekGiziGuest');
    });


    Route::group(['prefix'=>'kalkulatorStunting'], function () {
        Route::post('/cekStuntingIbu', [kalkulatorStuntingController::class, 'cekStuntingIbu'])->name('cekStuntingIbu');
        Route::post('/cekStuntingAnak', [kalkulatorStuntingController::class, 'cekStuntingAnak'])->name('cekStuntingAnak');
        Route::post('/cekStuntingAnakGuest', [kalkulatorStuntingController::class, 'cekStuntingAnakGuest'])->name('cekStuntingAnakGuest');
    });


    Route::group(['prefix'=>'bayi'], function () {
        Route::get('/', [bayiController::class, 'index'])->name('bayi');
        Route::post('/storeBayi', [bayiController::class, 'storeBayi'])->name('storeBayi');
        Route::post('/updateBayi', [bayiController::class, 'updateBayi'])->name('updateBayi');
        Route::post('/deleteBayi', [bayiController::class, 'deleteBayi'])->name('deleteBayi');
    });


    Route::group(['prefix'=>'artikel'], function () {
        Route::get('/',[artikelController::class, 'index'])->name('index');
    });


    // PUSH NOTIFICATION
    // Route::post('/send-notification', [posyanduController::class, 'sendNotif']);
    Route::get('/send-notification', [AuthController::class, 'sendNotif']);
});

Route::middleware(['auth:api', 'role:2'])->group(function () {
    Route::get('menu', [menuController::class, 'index'])->name('menu');

    Route::group(['prefix'=>'posyandu'], function () {
        Route::post('/posyByBidan', [posyanduController::class, 'posyByBidan'])->name('posyByBidan');
        Route::post('/storePosyandu', [posyanduController::class, 'storePosyandu'])->name('storePosyandu');
        Route::post('/updatePosyandu', [posyanduController::class, 'updatePosyandu'])->name('updatePosyandu');
        Route::post('/deletePosyandu', [posyanduController::class, 'deletePosyandu'])->name('deletePosyandu');

        Route::post('/storeJadwal', [posyanduController::class, 'storeJadwal'])->name('storeJadwal');
        Route::post('/updateJadwal', [posyanduController::class, 'updateJadwal'])->name('updateJadwal');
        Route::post('/deleteJadwal', [posyanduController::class, 'deleteJadwal'])->name('deleteJadwal');
    });
});


// Route::group(['middleware' => 'api','prefix' => 'auth'], function ($router) {
//     Route::post('refresh', [AuthController::class,'refresh']);
//     Route::post('me', [AuthController::class,'me']);
// });