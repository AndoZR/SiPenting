<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\berat_badan;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Models\bayi;
use App\Models\data_stunt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class kalkulatorStuntingController extends Controller
{
    public function cekStuntingIbu(Request $request){
        $lila = $request->lila;
        $hb = $request->hb;
        $bbPraHamil = Auth::user()->bbPraHamil;
        $tinggiBadan = Auth::user()->tinggiBadan;
        $bbNow = $request->bbNow;

        // Menyimpan bb saat ini
        $user_id = Auth::user()->id;
        
        // Menyimpan berat badan saat ini ke database
        try {
            $berat = berat_badan::create([
                'bbNow' => $bbNow,
                'id_users' => $user_id
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return ResponseFormatter::error($e->getMessage(), "Data gagal disimpan. Kesalahan Server", 500);
        }

        // Define the threshold values
        $lilaThreshold = 23.5;
        $hbThreshold = 12;

        // Create an array to hold any potential issues
        $issues = [];

        // Check LILA
        if ($lila < $lilaThreshold){
            $issues["lila"][] = "Lingkar lengan terlalu rendah (LILA: $lila cm, harus minimal $lilaThreshold cm)";
        }

        // Check HB
        if ($hb < $hbThreshold){
            $issues["hb"][] = "Hemoglobin terlalu rendah (HB: $hb g/dL, harus minimal $hbThreshold g/dL)";
        }

        // cek bb
        $IMT = $bbPraHamil / (($tinggiBadan/100)**2);
        if ($IMT < 18.5){
            $issues["IMT"][] = "IMT Pra-Kemalihan: $IMT, Rekomendasi Peningkatan Berat Badan: 12.5 - 18 Kg";
        }elseif ($IMT >= 18.5 && $IMT <= 24.9){
            $issues["IMT"][] = "IMT Pra-Kemalihan: $IMT, Rekomendasi Peningkatan Berat Badan: 11.5 - 16 Kg";
        }elseif ($IMT >= 25 && $IMT <= 29.9){
            $issues["IMT"][] = "IMT Pra-Kemalihan: $IMT, Rekomendasi Peningkatan Berat Badan: 7 - 11.5 Kg";
        }elseif ($IMT >= 30){
            $issues["IMT"][] = "IMT Pra-Kemalihan: $IMT, Rekomendasi Peningkatan Berat Badan: 5 - 9 Kg";
        }

        // Cek Usia
        $usia = round(Carbon::parse(Auth::user()->tanggalLahir)->diffInYears(now()));
        if ($usia < 19){
            $issues["usia"][] = "Usia Ibu: $usia kurang dar 19th beresiko stunting, segera periksa ke bidan!";
        }elseif ($usia > 35){
            $issues["usia"][] = "Usia Ibu: $usia kurang dar 35th beresiko stunting, segera periksa ke bidan!";
        }

        // Return the response based on the findings
        if (!empty($issues)) {
            return ResponseFormatter::success($issues, "Data stunting Ibu berhasil diproses!");
        } else {
            return ResponseFormatter::success("Semua parameter berada dalam batas yang aman.");
        }
    }

    public function cekStuntingAnak(Request $request){
        $idUser = Auth::user()->id;
        $anak = bayi::where('id', $idUser)->first();
        $umur = round(Carbon::parse($anak->tanggalLahir)->diffInMonths(now()));        
        $tinggiBadan = $request->tinggiBadan;

        $heightStandard = data_stunt::where('Umur (bulan)', (int)$umur)->first();
        // Menghapus kolom "id" dan "kelamin" dari array $heightStandard
        unset($heightStandard['id']);
        unset($heightStandard['kelamin']);
        unset($heightStandard['Umur (bulan)']);

        $heightStandardArray = $heightStandard->toArray();

        $toleransiPersentase = 2; // Toleransi dalam persentase, misalnya 5%

        // Mencari nama kolom yang sesuai dengan input
        $columnName = '';
        foreach ($heightStandardArray as $key => $value) {

            // Menghitung toleransi tinggi badan berdasarkan persentase
            $valueFloat = (float) $value;
            $toleransi = $valueFloat * ($toleransiPersentase / 100);
        
            // Memeriksa apakah tinggi badan input mendekati nilai SD
            if ($tinggiBadan >= $valueFloat - $toleransi && $tinggiBadan <= $valueFloat + $toleransi) {
                $columnName = $key;
                break;
            }
        }

        if($tinggiBadan < $heightStandardArray['Panjang Badan (cm) -3 SD']){
            return ResponseFormatter::success('Sangat pendek (severely stunted)','Data telah diproses!');
        }elseif($tinggiBadan > $heightStandardArray['Panjang Badan (cm) +3 SD']){
            return ResponseFormatter::success('Tinggi','Data telah diproses!');
        };

        if($columnName == 'Panjang Badan (cm) -3 SD'){
            return ResponseFormatter::success('Sangat pendek (severely stunted)','Data telah diproses!');
        }elseif($columnName == 'Panjang Badan (cm) -2 SD' || $columnName == 'Panjang Badan (cm) -1 SD'){
            return ResponseFormatter::success('Pendek (stunted)','Data telah diproses!');
        }elseif($columnName == 'Panjang Badan (cm) Median' || $columnName == 'Panjang Badan (cm) +1 SD' || $columnName == 'Panjang Badan (cm) +1 SD'){
            return ResponseFormatter::success('Normal','Data telah diproses!');
        }elseif($columnName == 'Panjang Badan (cm) +3 SD'){
            return ResponseFormatter::success('Tinggi','Data telah diproses!');
        }

    }
}
