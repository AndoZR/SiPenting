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

    public function storePosyandu(Request $request) {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'lokasi' => 'required|string',
            'lat' => 'required|string',
            'lng' => 'required|string',
            'kontak' => 'required',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(null,$validator->errors(),422);
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
            return ResponseFormatter::error($e->getMessage(), "Data gagal disimpan. Kesalahan Server", 500);
        }
    }

    public function updatePosyandu(Request $request) {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string',
            'lokasi' => 'required|string',
            'lat' => 'required|string|max:50',
            'lng' => 'required|string|max:50',
            'kontak' => 'required',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(null,$validator->errors(),422);
        };

        try {
            $data = posyandu::find($request->idPosyandu);
            $data->update([
                'nama' => $request->nama,
                'lokasi' => $request->lokasi,
                'lat' => $request->lat,
                'lng' => $request->lng,
                'kontak' => $request->kontak,
            ]);

            return ResponseFormatter::success($data, "Data Posyandu Berhasil Diubah!");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return ResponseFormatter::error($e->getMessage(), "Data gagal disimpan. Kesalahan Server", 500);
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
    public function getJadwal() {
        try{
            $dataJadwal = jadwal_posyandu::get();
            return ResponseFormatter::success($dataJadwal, 'Berhasil Mendapatkan Data!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return ResponseFormatter::error($e->getMessage(), "Data gagal diproses. Kesalahan Server", 500);
        }
    }

    public function storeJadwal(Request $request) {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'waktu' => 'required|date_format:H:i:s',
            'deskripsi' => 'required|string',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(null,$validator->errors(),422);
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
            return ResponseFormatter::error($e->getMessage(), "Data gagal disimpan. Kesalahan Server", 500);
        }
    }

    public function updateJadwal(Request $request) {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'waktu' => 'required|date_format:H:i:s',
            'deskripsi' => 'required|string',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(null,$validator->errors(),422);
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
            return ResponseFormatter::error($e->getMessage(), "Data gagal disimpan. Kesalahan Server", 500);
        }
    }

    public function deleteJadwal(Request $request){
        try{
            $data = jadwal_posyandu::find($request->idJadwal);
            $data->delete();
            return ResponseFormatter::success("Data Jadwal Berhasil Dihapus!");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return ResponseFormatter::error($e->getMessage(), "Data gagal dihapus. Kesalahan Server", 500);
        }
    }
}
