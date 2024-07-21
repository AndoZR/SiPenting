<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\artikel;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class artikelController extends Controller
{
    public function index() {
        try{
            $dataArtikel = artikel::get();
            return ResponseFormatter::success($dataArtikel, 'Berhasil Mendapatkan Data Artikel!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return ResponseFormatter::error($e->getMessage(), "Data gagal diproses. Kesalahan Server", 500);
        }
    }

    public function storeArtikel(Request $request) {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:100',
            'deskripsi' => 'required|string',
            'gambar' => 'required|max:5000|mimes:png,jpg',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(null,$validator->errors(),422);
        };

        try {
            if($request->file('gambar')){
                $nameGambar = time() . '_' . $request->file('gambar')->getClientOriginalName();
                Storage::putFileAs('public/artikel', $request->file('gambar'), $nameGambar);
            }
            
            $data = artikel::create([
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'gambar' => $nameGambar,
            ]);

            return ResponseFormatter::success($data, "Data Artikel Berhasil Dibuat!");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return ResponseFormatter::error($e->getMessage(), "Data gagal disimpan. Kesalahan Server", 500);
        }
    }

    public function updateArtikel(Request $request) {
        $validator = Validator::make($request->all(), [
            'judul' => 'string|max:50',
            'deskripsi' => 'string',
            'gambar' => 'max:5000|mimes:png,jpg',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(null,$validator->errors(),422);
        };

        try {
            $data = artikel::find($request->idArtikel);
            $updateData = $request->only(['judul', 'deskripsi', 'gambar']);

            // Filter out fields that are not present in the request or are null
            $updateData = array_filter($updateData, function ($value) {
                return !is_null($value);
            });
    
            if (!empty($updateData)) {
                $data->update($updateData);
            }
            return ResponseFormatter::success($data, "Data Artikel Berhasil Diubah!");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return ResponseFormatter::error($e->getMessage(), "Data gagal disimpan. Kesalahan Server", 500);
        }
    }

    public function deleteArtikel(Request $request){
        try{
            $data = artikel::find($request->idArtikel);
            $data->delete();
            return ResponseFormatter::success("Data Artikel Berhasil Dihapus!");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return ResponseFormatter::error($e->getMessage(), "Data gagal dihapus. Kesalahan Server", 500);
        }
    }
}
