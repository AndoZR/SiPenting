<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\makanan;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class kalkulatorGiziController extends Controller
{
    public function getMakanan() {
        $dataMakanan = makanan::get();
        return ResponseFormatter::success($dataMakanan, 'Data Makanan Berhasil Terkirim');
    }

    public function cekGizi(Request $request) {   
        $dataMakanan = $request->data;
        // $dataMakanan = $request->request->all();
        $collectMakanan = [];
        $collectSdm = [];

        // Menguraikan JSON menjadi array PHP
        $dataMakanan = json_decode($dataMakanan, true);

        // Memastikan JSON berhasil diuraikan
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['error' => 'Invalid JSON'], 400);
        }

        // Mencari selisih bulan(Umur Bayi)
        $umurBayi = round(Carbon::parse(Auth::user()->tanggalLahirBayi)->diffInMonths(now()));

        foreach($dataMakanan as $item){
            $collectMakanan[] = $item[0];
            $collectSdm[] = $item[1];
        }

        $hasil = $this->cekMakan($dataMakanan,$umurBayi,$collectMakanan);

        return ResponseFormatter::success($hasil, "Data Perhitungan Berhasil Didapatkan!");
    }

    private function cekMakan($dataMakanan,$umurBayi,$collectMakanan){
        if ($umurBayi >= 6 && $umurBayi <= 8){
            $cekSdm = $this->cekSdm($dataMakanan,2,3,$collectMakanan);
        }elseif($umurBayi >= 9 && $umurBayi <= 11){
            $cekSdm = $this->cekSdm($dataMakanan,3,4,$collectMakanan);
        }elseif($umurBayi >= 12 && $umurBayi <= 23){
            $cekSdm = $this->cekSdm($dataMakanan,4,5,$collectMakanan);
        }else{
            return "Data umur balita belum mencukupi minimal 6 bulan!";
        }
        return $cekSdm;
    }

    private function cekSdm($dataMakanan,$sdm1,$sdm2,$collectMakanan){
        $hasil = [];
        $dbMakananExc = makanan::whereNotIn("id", $collectMakanan)->where('id', '!=', 1)->get("nama");
        foreach($dbMakananExc as $x){
            $hasil["makanExcept"][] = $x->nama . " Tidak Terpenuhi!";
        }

        $collectNamaMakanan = [];
        $dbMakananTersedia = makanan::whereIn("id", $collectMakanan)->get("nama");
        foreach($dbMakananTersedia as $y){
            $collectNamaMakanan[] = $y->nama;
        }
        
        $index = 0;
        foreach($dataMakanan as $item) {
            $item[0] = $collectNamaMakanan[$index];

            if($item[0] == "Air Mineral"){ //exception buat air mineral, cus gak make sdm
                $hasil["sdm"][] = "Konsumsi $item[0] cukup!";
            }elseif(in_array($item[1], range($sdm1,$sdm2))){
                $hasil["sdm"][] = "Konsumsi $item[0] cukup!";
            }elseif($item[1] < $sdm1){
                $hasil["sdm"][] = "Konsumsi $item[0] terlalu sedikit!";
            }elseif($item[1] > $sdm2){
                $hasil["sdm"][] = "Konsumsi $item[0] terlalu banyak!";
            }
            $index++;
        }
        return $hasil;
    }
}
