<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\posyandu;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Models\jadwal_posyandu;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class posyanduController extends Controller
{
    public function index() {
        try{
            $dataPosyandu = posyandu::get();
            return ResponseFormatter::success($dataPosyandu, 'Berhasil Mendapatkan Data!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return ResponseFormatter::error($e->getMessage(), "Data gagal diproses. Kesalahan Server", 500);
        }
    }

    public function posyByBidan(Request $request) {
        $id_bidan = $request->id_bidan;
        try{
            $dataPosyandu = posyandu::where('id_users',$id_bidan)->get();
            return ResponseFormatter::success($dataPosyandu, 'Berhasil Mendapatkan Data Posyandu By Bidan!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return ResponseFormatter::error(null, $e->getMessage(), 500);
        }
    }

    public function storePosyandu(Request $request) {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'lokasi' => 'required|string',
            'lat' => 'required|string',
            'lng' => 'required|string',
            'kontak' => 'required|int',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();;
            return ResponseFormatter::error(null,$error[0],422);
        };

        try {
            $data = posyandu::create([
                'nama' => $request->nama,
                'lokasi' => $request->lokasi,
                'lat' => $request->lat,
                'lng' => $request->lng,
                'kontak' => $request->kontak,
                'id_users' => Auth::user()->id,
            ]);

            return ResponseFormatter::success($data, "Data Posyandu Berhasil Dibuat!");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return ResponseFormatter::error(null, $e->getMessage(), 500);
        }
    }

    public function updatePosyandu(Request $request) {
        $validator = Validator::make($request->all(), [
            'nama' => 'string|nullable',
            'lokasi' => 'string|nullable',
            'lat' => 'string|max:50|nullable',
            'lng' => 'string|max:50|nullable',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();;
            return ResponseFormatter::error(null,$error[0],422);
        };

        try {
            $data = posyandu::find($request->idPosyandu);
            $updateData = $request->only(['nama', 'lokasi', 'lat', 'lng', 'kontak']);

            // Filter out fields that are not present in the request or are null
            $updateData = array_filter($updateData, function ($value) {
                return !is_null($value);
            });
    
            if (!empty($updateData)) {
                $data->update($updateData);
            }
            return ResponseFormatter::success($data, "Data Posyandu Berhasil Diubah!");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return ResponseFormatter::error(null, $e->getMessage(), 500);
        }
    }

    public function deletePosyandu(Request $request){
        try{
            $data = posyandu::find($request->idPosyandu);
            $data->delete();
            return ResponseFormatter::success("Data Posyandu Berhasil Dihapus!");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return ResponseFormatter::error($e->getMessage(), "Data gagal dihapus. Kesalahan Server", 500);
        }
    }

    // Jadwal posyandu
    public function getJadwal(Request $request) {
        try{
            if($request->id_posyandu){
                $dataJadwal = jadwal_posyandu::where('id_posyandu', $request->id_posyandu)->get();
            }else{
                $dataJadwal = jadwal_posyandu::get();
            }
        
            return ResponseFormatter::success($dataJadwal, 'Berhasil Mendapatkan Data!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return ResponseFormatter::error(null, $e->getMessage(), 500);
        }
    }

    public function storeJadwal(Request $request) {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'waktu' => 'required|date_format:H:i:s',
            'deskripsi' => 'required|string',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();;
            return ResponseFormatter::error(null,$error[0],422);
        };

        try {
            $data = jadwal_posyandu::create([
                'tanggal' => $request->tanggal,
                'waktu' => $request->waktu,
                'deskripsi' => $request->deskripsi,
                'id_users' => Auth::user()->id,
                'id_posyandu' => $request->idPosyandu,
            ]);

            return ResponseFormatter::success($data, "Data Jadwal Berhasil Dibuat!");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return ResponseFormatter::error(null, $e->getMessage(), 500);
        }
    }

    public function updateJadwal(Request $request) {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'waktu' => 'required|date_format:H:i:s',
            'deskripsi' => 'required|string',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();;
            return ResponseFormatter::error(null,$error[0],422);
        };

        try {
            $data = jadwal_posyandu::find($request->idJadwal);
            $data->update([
                'tanggal' => $request->tanggal,
                'waktu' => $request->waktu,
                'deskripsi' => $request->deskripsi,
                'id_users' => Auth::user()->id,
                'id_posyandu' => $request->idPosyandu,
            ]);

            return ResponseFormatter::success($data, "Data Jadwal Berhasil Diubah!");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return ResponseFormatter::error(null, $e->getMessage(), 500);
        }
    }

    public function deleteJadwal(Request $request){
        try{
            $data = jadwal_posyandu::find($request->idJadwal);
            $data->delete();
            return ResponseFormatter::success("Data Jadwal Berhasil Dihapus!");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return ResponseFormatter::error(null, $e->getMessage(), 500);
        }
    }
}
