<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\bayi;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Validator;

class bayiController extends Controller
{
    public function index(){
        try {
            $id = Auth::user()->id;
            $dataBayi = bayi::where("id_users",$id)->get();

            foreach($dataBayi as $bayi){
                $tanggalLahir = Carbon::parse($bayi->tanggalLahir);

                // Mendapatkan tanggal saat ini
                $tanggalSekarang = Carbon::now();
                
                // Menghitung perbedaan tahun, bulan, dan hari
                $diff = $tanggalSekarang->diff($tanggalLahir);
                
                $year = $diff->y;
                $month = $diff->m;
                $day = $diff->d;
                
                $umurArray = [$year, $month, $day];
                $bayi->umur = $umurArray;
            }

            return ResponseFormatter::success($dataBayi,'Data Bayi Berhasil Didapat!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return ResponseFormatter::error(null, $e->getMessage(), 500);
        }
    }

    public function storeBayi(Request $request){
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'tanggalLahir' => 'required|date',
            'kelamin' => 'required|string',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            return ResponseFormatter::error(null, $errors[0], 422);
        };

        try {
            $test = bayi::create([
                'nama' => $request->nama,
                'tanggalLahir' => $request->tanggalLahir,
                'kelamin' => $request->kelamin,
                'id_users' => Auth::user()->id,
            ]);

            return ResponseFormatter::success($test, "Data Bayi Berhasil Dibuat!");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return ResponseFormatter::error(null, $e->getMessage(), 500);
        }
    }

    public function updateBayi(Request $request){
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'tanggalLahir' => 'required|date',
            'kelamin' => 'required|string',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();;
            return ResponseFormatter::error(null,$error[0],422);
        };

        try {
            $test = bayi::find($request->idBayi);
            $test->update([
                'nama' => $request->nama,
                'tanggalLahir' => $request->tanggalLahir,
                'kelamin' => $request->kelamin,
            ]);

            return ResponseFormatter::success($test, "Data Bayi Berhasil Diubah!");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return ResponseFormatter::error(null, $e->getMessage(), 500);
        }
    }

    public function deleteBayi(Request $request){
        try{
            $test = bayi::find($request->idBayi);
            $test->delete();
            return ResponseFormatter::success("Data Bayi Berhasil Dihapus!");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return ResponseFormatter::error(null, $e->getMessage(), 500);
        }
    }
}
