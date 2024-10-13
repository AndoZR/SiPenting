<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\bayi;
use App\Models\makanan;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
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
            $umurBayi = round(Carbon::parse($bayi->tanggalLahir)->diffInMonths(now()));
    
            foreach($dataMakanan as $item){
                $collectMakanan[] = $item[0];
                $collectSdm[] = $item[1];
            }
            // dd($collectMakanan);
    
            $hasil = $this->cekMakan($dataMakanan,$umurBayi,$collectMakanan);
    
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
                        $cekSdm[$key]['keterangan'] .= "! Umur $umurBayi bulan, disaran minum ±$gelas gelas (800 mL)! Segera konsultasi!";
                    }else{
                        $cekSdm[$key]['keterangan'] .= "! Pertahankan ya!";
                    }
                }else{
                    if($cekSdm[$key]['keterangan'] == 'Kurang' || $cekSdm[$key]['keterangan'] == 'Berlebihan'){
                        $cekSdm[$key]['keterangan'] .= "! Umur $umurBayi bulan, disaran makan $sdm1-$sdm2 sendok makan! Segera konsultasi!";
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
                        $cekSdm[$key]['keterangan'] .= "! Umur $umurBayi bulan, disaran minum ±3½ gelas (900 mL)! Segera konsultasi!";
                    }else{
                        $cekSdm[$key]['keterangan'] .= "! Pertahankan ya!";
                    }
                }else{
                    if($cekSdm[$key]['keterangan'] == 'Kurang' || $cekSdm[$key]['keterangan'] == 'Berlebihan'){
                        $cekSdm[$key]['keterangan'] .= "! Umur $umurBayi bulan, disaran makan $sdm1-$sdm2 sendok makan! Segera konsultasi!";
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
                        $cekSdm[$key]['keterangan'] .= "! Umur $umurBayi bulan, disaran minum ±$gelas gelas (1300 mL)! Segera konsultasi!";
                    }else{
                        $cekSdm[$key]['keterangan'] .= "! Pertahankan ya!";
                    }
                }else{
                    if($cekSdm[$key]['keterangan'] == 'Kurang' || $cekSdm[$key]['keterangan'] == 'Berlebihan'){
                        $cekSdm[$key]['keterangan'] .= "! Umur $umurBayi bulan, disaran makan $sdm1-$sdm2 sendok makan! Segera konsultasi!";
                    }else{
                        $cekSdm[$key]['keterangan'] .= "! Pertahankan ya!";
                    }
                }
            }

        }else{
            return ["Data umur balita haraus 6 hingga 59 bulan!"];
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

    // private function cekMakanOld($dataMakanan,$umurBayi,$collectMakanan){
    //     if ($umurBayi >= 6 && $umurBayi <= 8){
    //         $cekSdm = $this->cekSdm($dataMakanan,2,3,$collectMakanan);
    //     }elseif($umurBayi >= 9 && $umurBayi <= 11){
    //         $cekSdm = $this->cekSdm($dataMakanan,3,4,$collectMakanan);
    //     }elseif($umurBayi >= 12 && $umurBayi <= 23){
    //         $cekSdm = $this->cekSdm($dataMakanan,4,5,$collectMakanan);
    //     }else{
    //         return "Data umur balita haraus 6 hingga 23 bulan!";
    //     }
    //     return $cekSdm;
    // }

    // private function cekSdmOld($dataMakanan,$sdm1,$sdm2,$collectMakanan){
    //     $hasil = [];

    //     $dbMakananExc = makanan::whereNotIn("id", $collectMakanan)->where('id', '!=', 1)->get("nama");
    //     foreach($dbMakananExc as $x){
    //         $hasil["makanExcept"][] = $x->nama . " Tidak Terpenuhi!";
    //     }

    //     $collectNamaMakanan = [];
    //     $dbMakananTersedia = makanan::whereIn("id", $collectMakanan)->get("nama");
    //     // $dbMakananTersedia = makanan::get("nama");
    //     foreach($dbMakananTersedia as $y){
    //         $collectNamaMakanan[] = $y->nama;
    //     }
        
    //     $index = 0;
    //     foreach($dataMakanan as $item) {
    //         $item[0] = $collectNamaMakanan[$index];

    //         if ($item[1] == 0){
    //             $hasil["makanExcept"][] = $item[0] . " Tidak Terpenuhi!";
    //             $index++;
    //             continue;
    //         } // kalo ada sdm 0 return tidak terpenuhi

    //         if($item[0] == "Air Mineral"){ //exception buat air mineral, cus gak make sdm
    //             $hasil["sdm"][] = "Konsumsi $item[0] cukup!";
    //         }elseif(in_array($item[1], range($sdm1,$sdm2))){
    //             $hasil["sdm"][] = "Konsumsi $item[0] cukup!";
    //         }elseif($item[1] < $sdm1){
    //             $hasil["sdm"][] = "Konsumsi $item[0] terlalu sedikit!";
    //         }elseif($item[1] > $sdm2){
    //             $hasil["sdm"][] = "Konsumsi $item[0] terlalu banyak!";
    //         }
    //         $index++;
    //     }
    //     return $hasil;
    // }
}
