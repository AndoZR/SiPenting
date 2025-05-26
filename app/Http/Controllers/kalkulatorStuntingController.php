<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\bayi;
use App\Models\data_stunt;
use App\Models\berat_badan;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Models\hist_stun;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class kalkulatorStuntingController extends Controller
{
    public function cekStuntingIbu(Request $request){
        $validator = Validator::make($request->all(), [
            'lila' => 'required|numeric',
            'hb' => 'required|numeric',
            'bbNow' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();;
            return ResponseFormatter::error(null,$error[0],422);
        };

        $lila = $request->lila;
        $hb = $request->hb;
        $bbNow = $request->bbNow;

        $bbPraHamil = Auth::user()->bbPraHamil;
        $tinggiBadan = Auth::user()->tinggiBadan;

        // Menyimpan bb saat ini
        $user_id = Auth::user()->id;
        
        // Menyimpan berat badan saat ini ke database
        $this->simpanBeratBadan($bbNow, $user_id);

        // Define the threshold values
        $lilaThreshold = 23.5;

        // Create an array to hold any potential issues
        $issues = [];

        // Check LILA
        if ($lila < $lilaThreshold){
            $issues["lila"] = "Lingkar lengan atas terlalu rendah! (LILA: $lila cm, rekomendasi minimal $lilaThreshold cm)";
        }else{
            $issues["lila"] = "Pertahankan! (LILA: $lila cm, harus minimal $lilaThreshold cm)";
        }

        // Check HB
        if ($hb == 1){
            $issues["hb"] = "Hemoglobin normal";
        }
        elseif ($hb == 2){
            $issues["hb"] = "Status Anda anemia ringan, rekomendasi HB Normal diatas 11!";
        }
        elseif ($hb == 3){
            $issues["hb"] = "Status Anda anemia sedang, rekomendasi HB Normal diatas 11!";
        }
        elseif ($hb == 4){
            $issues["hb"] = "Status Anda anemia berat, rekomendasi HB Normal diatas 11!";
        }
        elseif ($hb == 5){
            $issues["hb"] = "Segera cek kadar Hemoglobin di Puskesmas terdekat!";
        }

        // cek parameter tinggi badan dan bbprahamil
        if(!isset($bbPraHamil) || $bbPraHamil == 0 || !isset($tinggiBadan) || $tinggiBadan == 0){
            $issues["IMT"] = "Data Berat Badan PraHamil / Tinggi Badan tidak boleh kosong, Silahkan lengkapi profil atau konsultasi ke Puskesmas!";
        }
        else{
            // $IMT = $bbPraHamil / (($tinggiBadan/100)**2);
            $IMT = number_format($bbPraHamil / (($tinggiBadan / 100) ** 2), 2);

            if ($IMT < 18.5){
                $issues["IMT"] = "Anda Memiliki Tinggi (". intval($tinggiBadan) . ") dan Berat Badan PraHamil (" .intval($bbPraHamil) . ") Sehingga IMT PraKehamilan: $IMT, rekomendasi Peningkatan Berat Badan: 12.5 - 18 Kg";
            }elseif ($IMT >= 18.5 && $IMT <= 24.9){
                $issues["IMT"] = "Anda Memiliki Tinggi (". intval($tinggiBadan) . ") dan Berat Badan PraHamil (" .intval($bbPraHamil) . ") Sehingga IMT PraKehamilan: $IMT, rekomendasi Peningkatan Berat Badan: 11.5 - 16 Kg";
            }elseif ($IMT >= 25 && $IMT <= 29.9){
                $issues["IMT"] = "Anda Memiliki Tinggi (". intval($tinggiBadan) . ") dan Berat Badan PraHamil (" .intval($bbPraHamil) . ") Sehingga IMT PraKehamilan: $IMT, rekomendasi Peningkatan Berat Badan: 7 - 11.5 Kg";
            }elseif ($IMT >= 30){
                $issues["IMT"] = "Anda Memiliki Tinggi (". intval($tinggiBadan) . ") dan Berat Badan PraHamil (" .intval($bbPraHamil) . ") Sehingga IMT PraKehamilan: $IMT, rekomendasi Peningkatan Berat Badan: 5 - 9 Kg";
            }
        }

        // Cek Usia
        $usia = round(Carbon::parse(Auth::user()->tanggalLahir)->diffInYears(now()));
        if ($usia < 19){
            $issues["usia"] = "Usia Ibu: $usia kurang dar 19th beresiko stunting, segera periksa ke bidan!";
        }elseif ($usia > 35){
            $issues["usia"] = "Usia Ibu: $usia kurang dar 35th beresiko stunting, segera periksa ke bidan!";
        }

        // Return the response based on the findings
        if (!empty($issues)) {
            return ResponseFormatter::success($issues, "Data stunting Ibu berhasil diproses!");
        } else {
            return ResponseFormatter::success("Kesalahan Server");
        }
    }

    // public function cekStuntingAnak(Request $request){
    //     try{
    //         $idBayi = $request->idBayi;
    //         $anak = bayi::where('id', $idBayi)->first();
    //         $umur = round(Carbon::parse($anak->tanggalLahir)->diffInMonths(now())); // menggunakan kode ini akan mengonversi menjadi bulan dengan memandang tanggal hariannya
    //         // $umur = (now()->year - Carbon::parse($anak->tanggalLahir)->year) * 12 
    //         //   + (now()->month - Carbon::parse($anak->tanggalLahir)->month); // menggunakan kode ini akan mengonversi menjadi bulan tanpa memandang tanggal hariannya
    //         $tinggiBadan = floatval($request->tinggiBadan);

    //         $heightStandard = data_stunt::where('Umur (bulan)', (int)$umur)->first();

    //         // Menghapus kolom "id" dan "kelamin" dari array $heightStandard
    //         unset($heightStandard['id']);
    //         unset($heightStandard['kelamin']);
    //         unset($heightStandard['Umur (bulan)']);
    //         $heightStandardArray = $heightStandard->toArray();

    //         $hasil = [];

    //         if($tinggiBadan < floatval($heightStandardArray['Panjang Badan (cm) -3 SD'])){
    //             $hasil["status"] = 1;
    //             $hasil["rekomendasi"] = "Segera periksa ke Puskesmas, pastikan konsumsi protein hewani tercukupi!";
    //             return ResponseFormatter::success($hasil,'Data telah diproses!');
    //         }elseif($tinggiBadan >= floatval($heightStandardArray['Panjang Badan (cm) -3 SD']) && $tinggiBadan < floatval($heightStandardArray['Panjang Badan (cm) -2 SD'])){
    //             $hasil["status"] = 2;
    //             $hasil["rekomendasi"] = "Segera periksa ke Puskesmas, pastikan konsumsi protein hewani tercukupi!";
    //             return ResponseFormatter::success($hasil,'Data telah diproses!');
    //         }elseif($tinggiBadan >= floatval($heightStandardArray['Panjang Badan (cm) -2 SD']) && $tinggiBadan <= floatval($heightStandardArray['Panjang Badan (cm) +3 SD'])){
    //             $hasil["status"] = 3;
    //             $hasil["rekomendasi"] = "Pertahankan!";
    //             return ResponseFormatter::success($hasil,'Data telah diproses!');
    //         }elseif($tinggiBadan > floatval($heightStandardArray['Panjang Badan (cm) +3 SD'])){
    //             $hasil["status"] = 4;
    //             $hasil["rekomendasi"] = "Pastikan cara mengukur anak Anda benar!";
    //             return ResponseFormatter::success($hasil,'Data telah diproses!');
    //         }

    //         // sesi simpan data untuk admin bapeda
    //         hist_stun::updateOrCreate(
    //             [
    //                 'id_bayi' => $request->idBayi,
    //                 'tanggal' => now()->toDateString() // pastikan hanya tanggalnya saja yang dicek
    //             ],
    //             [
    //                 'jenis' => $hasil['status']
    //             ]
    //         );


    //     }catch(Exception $e){
    //         Log::error($e->getMessage());
    //         return ResponseFormatter::error(null, $e->getMessage(), 500);
    //     };
    // }

    // eficient code
    public function cekStuntingAnak(Request $request){
        try {
            $idBayi = $request->idBayi;
            $anak = bayi::findOrFail($idBayi);

            $umur = round(Carbon::parse($anak->tanggalLahir)->diffInMonths(now()));
            $tinggiBadan = floatval($request->tinggiBadan);

            $heightStandard = data_stunt::where('Umur (bulan)', $umur)->first();
            if (!$heightStandard) {
                return ResponseFormatter::error(null, 'Data standar tidak ditemukan untuk umur tersebut.', 404);
            }

            $heightStandardArray = $heightStandard->only([
                'Panjang Badan (cm) -3 SD',
                'Panjang Badan (cm) -2 SD',
                'Panjang Badan (cm) +3 SD'
            ]);

            $pbMin3SD = floatval($heightStandardArray['Panjang Badan (cm) -3 SD']);
            $pbMin2SD = floatval($heightStandardArray['Panjang Badan (cm) -2 SD']);
            $pbPlus3SD = floatval($heightStandardArray['Panjang Badan (cm) +3 SD']);

            $hasil = ['status' => 0, 'rekomendasi' => ''];

            if ($tinggiBadan < $pbMin3SD) {
                $hasil['status'] = 1;
                $hasil['rekomendasi'] = "Segera periksa ke Puskesmas, pastikan konsumsi protein hewani tercukupi!";
            } elseif ($tinggiBadan < $pbMin2SD) {
                $hasil['status'] = 2;
                $hasil['rekomendasi'] = "Segera periksa ke Puskesmas, pastikan konsumsi protein hewani tercukupi!";
            } elseif ($tinggiBadan <= $pbPlus3SD) {
                $hasil['status'] = 3;
                $hasil['rekomendasi'] = "Pertahankan!";
            } else {
                $hasil['status'] = 4;
                $hasil['rekomendasi'] = "Pastikan cara mengukur anak Anda benar!";
            }

            hist_stun::updateOrCreate(
                [
                    'id_bayi' => $idBayi,
                    'tanggal' => now()->toDateString()
                ],
                [
                    'jenis' => $hasil['status']
                ]
            );

            return ResponseFormatter::success($hasil, 'Data telah diproses!');
            
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return ResponseFormatter::error(null, $e->getMessage(), 500);
        }
    }


    protected function simpanBeratBadan($bbNow,$user_id)
    {
        // Ambil bulan dan tahun saat ini menggunakan Carbon
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        try {
            // Cek apakah user sudah memiliki catatan di bulan dan tahun ini
            $berat = berat_badan::where('id_users', $user_id)
                ->whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->first();

            if ($berat) {
                // Jika sudah ada, lakukan update
                $berat->update([
                    'bbNow' => $bbNow,
                ]);
            } else {
                // Jika belum ada, buat data baru
                $berat = berat_badan::create([
                    'bbNow' => $bbNow,
                    'id_users' => $user_id,
                ]);
            }
        } catch (Exception $e) {
            // Tangkap error jika ada
            Log::error($e->getMessage());
            return ResponseFormatter::error(null, $e->getMessage(), 500);
        }
    }

    // GUEST MODE
    public function cekStuntingAnakGuest(Request $request){
        
        try{
            $tinggiBadan = floatval($request->tinggiBadan);
            $tglLahir = $request->tglLahir;
            $umur = round(Carbon::parse($tglLahir)->diffInMonths(now())); // menggunakan kode ini akan mengonversi menjadi bulan dengan memandang tanggal hariannya
            // $umur = (now()->year - Carbon::parse($anak->tanggalLahir)->year) * 12 
            //   + (now()->month - Carbon::parse($anak->tanggalLahir)->month); // menggunakan kode ini akan mengonversi menjadi bulan tanpa memandang tanggal hariannya

            $heightStandard = data_stunt::where('Umur (bulan)', (int)$umur)->first();

            // Menghapus kolom "id" dan "kelamin" dari array $heightStandard
            unset($heightStandard['id']);
            unset($heightStandard['kelamin']);
            unset($heightStandard['Umur (bulan)']);
            $heightStandardArray = $heightStandard->toArray();

            $hasil = [];

            if($tinggiBadan < floatval($heightStandardArray['Panjang Badan (cm) -3 SD'])){
                $hasil["status"] = 1;
                $hasil["rekomendasi"] = "Segera periksa ke Puskesmas, pastikan konsumsi protein hewani tercukupi!";
                return ResponseFormatter::success($hasil,'Data telah diproses!');
            }elseif($tinggiBadan >= floatval($heightStandardArray['Panjang Badan (cm) -3 SD']) && $tinggiBadan < floatval($heightStandardArray['Panjang Badan (cm) -2 SD'])){
                $hasil["status"] = 2;
                $hasil["rekomendasi"] = "Segera periksa ke Puskesmas, pastikan konsumsi protein hewani tercukupi!";
                return ResponseFormatter::success($hasil,'Data telah diproses!');
            }elseif($tinggiBadan >= floatval($heightStandardArray['Panjang Badan (cm) -2 SD']) && $tinggiBadan <= floatval($heightStandardArray['Panjang Badan (cm) +3 SD'])){
                $hasil["status"] = 3;
                $hasil["rekomendasi"] = "Pertahankan!";
                return ResponseFormatter::success($hasil,'Data telah diproses!');
            }elseif($tinggiBadan > floatval($heightStandardArray['Panjang Badan (cm) +3 SD'])){
                $hasil["status"] = 4;
                $hasil["rekomendasi"] = "Pastikan cara mengukur anak Anda benar!";
                return ResponseFormatter::success($hasil,'Data telah diproses!');
            }
        }catch(Exception $e){
            Log::error($e->getMessage());
            return ResponseFormatter::error(null, $e->getMessage(), 500);
        };
    }
}
 