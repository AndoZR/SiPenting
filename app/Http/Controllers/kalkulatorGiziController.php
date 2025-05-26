<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\bayi;
use App\Models\makanan;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Models\hist_gizi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class kalkulatorGiziController extends Controller
{
    public function getMakanan() {
        try{
            $dataMakanan = makanan::get();
            return ResponseFormatter::success($dataMakanan, 'Data Makanan Berhasil Terkirim');
        }catch(Exception $e){
            Log::error($e->getMessage());
            return ResponseFormatter::error(null, $e->getMessage(), 500);
        }
    }

    public function cekGizi(Request $request) {   
        try{
            $dataMakanan = $request->data;
            // $dataMakanan = $request->request->all();
            $collectMakanan = [];
            $collectSdm = [];
            $bayi = bayi::where('id',$request->idBayi)->first();
    
            // Menguraikan JSON menjadi array PHP
            $dataMakanan = json_decode($dataMakanan, true);
    
            // Memastikan JSON berhasil diuraikan
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json(['error' => 'Invalid JSON'], 400);
            }
    
            // Mencari selisih bulan(Umur Bayi)
            // $umurBayi = round(Carbon::parse($bayi->tanggalLahir)->diffInMonths(now()));
            
            $umurBayi = (now()->year - Carbon::parse($bayi->tanggalLahir)->year) * 12 
          + (now()->month - Carbon::parse($bayi->tanggalLahir)->month);

            foreach($dataMakanan as $item){
                $collectMakanan[] = $item[0];
                $collectSdm[] = $item[1];
            }
    
            $hasil = $this->cekMakan($dataMakanan,$umurBayi,$collectMakanan);

            // sesi simpan data untuk admin bapeda
            hist_gizi::updateOrCreate(
                [
                    'id_bayi' => $request->idBayi,
                    'tanggal' => now()->toDateString() // pastikan hanya tanggalnya saja yang dicek
                ],
                [
                    'nilai_gizi' => json_encode([$hasil[0]['kecukupan'],$hasil[1]['kecukupan'],$hasil[2]['kecukupan'],$hasil[3]['kecukupan'],$hasil[4]['kecukupan']])
                ]
            );
    
            return ResponseFormatter::success($hasil, "Data Perhitungan Berhasil Didapatkan!");
        }catch(Exception $e){
            Log::error($e->getMessage());
            return ResponseFormatter::error(null, $e->getMessage(), 500);
        };
    }

    private function cekMakan($dataMakanan,$umurBayi,$collectMakanan){
        if ($umurBayi >= 6 && $umurBayi <= 8){
            $sdm1 = 2;
            $sdm2 = 5;
            $gelas = 3;

            $cekSdm = $this->cekSdm($dataMakanan,$sdm1,$sdm2,$collectMakanan,3); // cek sdm (data makanan dan sdm yang masuk dari kalkulator, batas sdm bawah, batas sdm atas, data makanan idnya aja)
            
            // aku ingin memberi rekomendasi setiap umur pada "keterangan"
            foreach ($cekSdm as $key => $value) {
                if($cekSdm[$key]['makanan'] == "Cairan (Air, Susu, dll)"){
                    if($cekSdm[$key]['keterangan'] == 'Kurang' || $cekSdm[$key]['keterangan'] == 'Berlebihan'){
                        $cekSdm[$key]['keterangan'] .= "! Umur $umurBayi bulan, disarankan minum ±$gelas gelas (800 mL) per hari! Segera konsultasi!";
                    }else{
                        $cekSdm[$key]['keterangan'] .= "! Pertahankan ya!";
                    }
                }else{
                    if($cekSdm[$key]['keterangan'] == 'Kurang' || $cekSdm[$key]['keterangan'] == 'Berlebihan'){
                        $cekSdm[$key]['keterangan'] .= "! Umur $umurBayi bulan, disarankan makan $sdm1-$sdm2 sendok makan per porsi! Segera konsultasi!";
                    }else{
                        $cekSdm[$key]['keterangan'] .= "! Pertahankan ya!";
                    }
                }
            }

        }elseif($umurBayi >= 9 && $umurBayi <= 11){
            $sdm1 = 5;
            $sdm2 = 7;
            $gelas = 4;

            $cekSdm = $this->cekSdm($dataMakanan,$sdm1,$sdm2,$collectMakanan,4);
            
            // aku ingin memberi rekomendasi setiap umur pada "keterangan"
            foreach ($cekSdm as $key => $value) {
                if($cekSdm[$key]['makanan'] == "Cairan (Air, Susu, dll)"){
                    if($cekSdm[$key]['keterangan'] == 'Kurang' || $cekSdm[$key]['keterangan'] == 'Berlebihan'){
                        $cekSdm[$key]['keterangan'] .= "! Umur $umurBayi bulan, disarankan minum ±3½ gelas (900 mL) per hari! Segera konsultasi!";
                    }else{
                        $cekSdm[$key]['keterangan'] .= "! Pertahankan ya!";
                    }
                }else{
                    if($cekSdm[$key]['keterangan'] == 'Kurang' || $cekSdm[$key]['keterangan'] == 'Berlebihan'){
                        $cekSdm[$key]['keterangan'] .= "! Umur $umurBayi bulan, disarankan makan $sdm1-$sdm2 sendok makan per porsi! Segera konsultasi!";
                    }else{
                        $cekSdm[$key]['keterangan'] .= "! Pertahankan ya!";
                    }
                }
            }

        }elseif($umurBayi >= 12 && $umurBayi <= 59){
            $sdm1 = 7;
            $sdm2 = 10;
            $gelas = 5;

            $cekSdm = $this->cekSdm($dataMakanan,$sdm1,$sdm2,$collectMakanan,5);
        
            // aku ingin memberi rekomendasi setiap umur pada "keterangan"
            foreach ($cekSdm as $key => $value) {
                if($cekSdm[$key]['makanan'] == "Cairan (Air, Susu, dll)"){
                    if($cekSdm[$key]['keterangan'] == 'Kurang' || $cekSdm[$key]['keterangan'] == 'Berlebihan'){
                        $cekSdm[$key]['keterangan'] .= "! Umur $umurBayi bulan, disarankan minum ±$gelas gelas (1300 mL) per hari! Segera konsultasi!";
                    }else{
                        $cekSdm[$key]['keterangan'] .= "! Pertahankan ya!";
                    }
                }else{
                    if($cekSdm[$key]['keterangan'] == 'Kurang' || $cekSdm[$key]['keterangan'] == 'Berlebihan'){
                        $cekSdm[$key]['keterangan'] .= "! Umur $umurBayi bulan, disarankan makan $sdm1-$sdm2 sendok makan per porsi! Segera konsultasi!";
                    }else{
                        $cekSdm[$key]['keterangan'] .= "! Pertahankan ya!";
                    }
                }
            }

        }else{
            $cekSdm = [
                [
                    'makanan'=>'Peringatan!',
                    "keterangan"=>"Data umur balita harus 6 hingga 59 bulan",
                    "kecukupan"=>"1"
                ]
            ];
            return $cekSdm;
        }
        return $cekSdm;
    }

    private function cekSdm($dataMakanan,$sdm1,$sdm2,$collectMakanan,$gelas){
        $hasil = [];

        // mencari makanan yang tidak ada di database maka dia kurang
        $dbMakananExc = makanan::whereNotIn("id", $collectMakanan)->get("nama");
        foreach($dbMakananExc as $x){
            $hasil[] = ["makanan" => $x->nama,
                        "keterangan" => "Kurang",
                        "kecukupan" => 1,
                        ];
        }

        $collectNamaMakanan = [];
        $dbMakananTersedia = makanan::whereIn("id", $collectMakanan)->get("nama");

        foreach($dbMakananTersedia as $y){
            $collectNamaMakanan[] = $y->nama;
        }
        
        $index = 0;
        foreach($dataMakanan as $item) {
            $item[0] = $collectNamaMakanan[$index];

            if ($item[1] == 0){
                $hasil[] = ["makanan" => $item[0],
                            "keterangan" => "Kurang",
                            "kecukupan" => 1,
                            ];
                $index++;
                continue;
            } // kalo ada sdm 0 return tidak terpenuhi

            if($item[0] == "Cairan (Air, Susu, dll)"){ //exception buat air mineral, dia make mili
                if($item[1] == $gelas){
                    $hasil[] = ["makanan" => $item[0],
                                "keterangan" => "Cukup",
                                "kecukupan" => 2,
                                ];
                }elseif($item[1] < $gelas){
                    $hasil[] = ["makanan" => $item[0],
                                "keterangan" => "Kurang",
                                "kecukupan" => 1,
                                ];
                }elseif($item[1] > $gelas){
                    $hasil[] = ["makanan" => $item[0],
                                "keterangan" => "Berlebihan",
                                "kecukupan" => 3,
                                ];
                }
                $index++;
                continue;
            }elseif(in_array($item[1], range($sdm1,$sdm2))){
                $hasil[] = ["makanan" => $item[0],
                            "keterangan" => "Cukup",
                            "kecukupan" => 2,
                            ];
            }elseif($item[1] < $sdm1){
                $hasil[] = ["makanan" => $item[0],
                            "keterangan" => "Kurang",
                            "kecukupan" => 1,
                            ];
            }elseif($item[1] > $sdm2){
                $hasil[] = ["makanan" => $item[0],
                            "keterangan" => "Berlebihan",
                            "kecukupan" => 3,
                            ];
            }
            $index++;
        }
        return $hasil;
    }


    // MODE GUEST
    public function cekGiziGuest(Request $request) {   
        try{
            $dataMakanan = $request->data;
            $collectMakanan = [];
            $collectSdm = [];
            $tglLahir = $request->tglLahir;
    
            // Menguraikan JSON menjadi array PHP
            $dataMakanan = json_decode($dataMakanan, true);
    
            // Memastikan JSON berhasil diuraikan
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json(['error' => 'Invalid JSON'], 400);
            }
    
            // Mencari selisih bulan(Umur Bayi)
            $umurBayi = round(Carbon::parse($tglLahir)->diffInMonths(now()));
            
        //     $umurBayi = (now()->year - Carbon::parse($tglLahir)->year) * 12 
        //   + (now()->month - Carbon::parse($tglLahir)->month);

            foreach($dataMakanan as $item){
                $collectMakanan[] = $item[0];
                $collectSdm[] = $item[1];
            }
    
            $hasil = $this->cekMakan($dataMakanan,$umurBayi,$collectMakanan);
    
            return ResponseFormatter::success($hasil, "Data Perhitungan Berhasil Didapatkan!");
        }catch(Exception $e){
            Log::error($e->getMessage());
            return ResponseFormatter::error(null, $e->getMessage(), 500);
        };
    }

}
