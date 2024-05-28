<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\makanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class kalkulatorGiziController extends Controller
{
    public function getMakanan() {
        $dataMakanan = makanan::with('jenis_gizi')->get();
        return ResponseFormatter::success($dataMakanan, 'Data Makanan Berhasil Terkirim');
    }

    // public function cekGizi(Request $request) {
    //     $validator = Validator::make($request->all(), [
    //         ''
    //     ])
    //     $dataPilihan = $request;


    //     return ResponseFormatter::success($dataPilihan);
    // }
}
