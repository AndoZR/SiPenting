<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\artikel;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use Carbon\Carbon;

class artikelController extends Controller
{
    public function index() {
        try {
            $dataArtikel = artikel::get();
    
            $dataArtikel->transform(function($artikel) { 
                // Format created_at ke format 'Hari, tanggal-nama bulan-yyyy'
                $artikel->formatted_created_at = Carbon::parse($artikel->created_at)->translatedFormat('l, d F Y');
                return $artikel;
            });
    
            return ResponseFormatter::success($dataArtikel, 'Berhasil Mendapatkan Data Artikel!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return ResponseFormatter::error($e->getMessage(), "Data gagal diproses. Kesalahan Server", 500);
        }
    }    

    public function viewArtikel(Request $request) {
        if($request->ajax()) {
            try{
                $dataArtikel = artikel::get();

                $dataArtikel->transform(function($artikel) {
                    // Format 'created_at' jika sudah merupakan objek Carbon
                    $artikel->created_at = Carbon::parse($artikel->created_at)->format('l, d F Y');
                    return $artikel;
                });

                return ResponseFormatter::success($dataArtikel, 'Berhasil Mendapatkan Data Artikel!');
            } catch (Exception $e) {
                Log::error($e->getMessage());
                return ResponseFormatter::error($e->getMessage(), "Data gagal diproses. Kesalahan Server", 500);
            }
        }
        return view('admin.artikel');
    }

    public function storeArtikel(Request $request) {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:100',
            'deskripsi' => 'required|string',
            'gambar' => 'required|max:3000|mimes:png,jpg',
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

    public function updateArtikel(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'judul' => 'string|max:100',
            'deskripsi' => 'string',
            'gambar' => 'max:3000|mimes:png,jpg',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(null,$validator->errors(),422);
        };

        try {
            $data = artikel::find($id);
            $updateData = $request->only(['judul', 'deskripsi']);

            if ($request->file('gambar')) {
                // Hapus gambar lama jika ada
                if ($data->gambar) {
                    Storage::delete('public/artikel/' . $data->gambar);
                }
                
                // Simpan gambar baru
                $nameGambar = time() . '_' . $request->file('gambar')->getClientOriginalName();
                $request->file('gambar')->storeAs('public/artikel', $nameGambar);
            
                // Tambahkan nama gambar ke data yang akan diupdate
                $updateData['gambar'] = $nameGambar;
            }
            
            // Update data artikel
            $data->update($updateData);

            return ResponseFormatter::success($data, "Data Artikel Berhasil Diubah!");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return ResponseFormatter::error($e->getMessage(), "Data gagal disimpan. Kesalahan Server", 500);
        }
    }

    public function deleteArtikel($id){
        try{
            $data = artikel::find($id);
            $data->delete();
            return ResponseFormatter::success("Data Artikel Berhasil Dihapus!");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return ResponseFormatter::error($e->getMessage(), "Data gagal dihapus. Kesalahan Server", 500);
        }
    }
}
