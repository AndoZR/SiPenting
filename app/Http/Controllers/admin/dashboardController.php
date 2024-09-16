<?php

namespace App\Http\Controllers\admin;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class dashboardController extends Controller
{
    public function index(){
        return view("admin.home");
    }

    public function daftar(Request $request){
        if($request->ajax()) {
            try {
                $data = User::where('role', 1)->with('villages')->get();
        
                return ResponseFormatter::success($data, 'Berhasil Mendapatkan Data Ibu!');
            } catch (Exception $e) {
                Log::error($e->getMessage());
                return ResponseFormatter::error($e->getMessage(), "Data gagal diproses. Kesalahan Server", 500);
            }
        }
        return view("admin.daftarIbu");
    }

    public function graph($id, Request $request){
        try {
            $dataBB = User::where('id', $id)
            ->with(['berat_badan' => function($query) {
                // Dapatkan tanggal 9 bulan yang lalu
                $nineMonthsAgo = Carbon::now()->subMonths(9);
        
                // Filter data berat_badan yang created_at-nya 9 bulan ke belakang
                $query->where('created_at', '>=', $nineMonthsAgo);
            }])
            ->first(); // Use first() instead of get() expecting a single user
    
            // return ResponseFormatter::success($dataBB, 'Berhasil Mendapatkan Data Berat Badan!');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return ResponseFormatter::error($e->getMessage(), "Data gagal diproses. Kesalahan Server", 500);
        }
        return view("admin.graphBB",['data' => $dataBB]);
    }
}
