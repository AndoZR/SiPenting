<?php

namespace App\Http\Controllers\admin;

use Exception;
use Carbon\Carbon;
use App\Models\bayi;
use App\Models\User;
use App\Models\villages;
use App\Models\districts;
use App\Exports\ExportData;
use Illuminate\Http\Request;
use App\Models\akun_puskesmas;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\hist_gizi;
use App\Models\hist_stun;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class dashboardController extends Controller
{
    public function index(){
        return view("admin.home");
    }

    public function viewAkunPuskesmas(Request $request){
        if($request->ajax()) {
            try{
                $dataAkun = akun_puskesmas::with('districts')->get();

                return ResponseFormatter::success($dataAkun,"Berhasil Mendapatkan Data Akun!");
            } catch (Exception $e) {
                Log::error($e->getMessage());
                return ResponseFormatter::error($e->getMessage(), "Data gagal diproses. Kesalahan Server", 500);
            }
        }
        $dataKecamatan = districts::where("regency_id",3511)->get();
        return view('admin.akunPuskesmas', ["dataKecamatan" => $dataKecamatan]);
    }

    public function addPuskesmas(Request $request) {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:100',
            'nomor' => 'required|string|regex:/^[0-9]{10,15}$/|unique:akun_puskesmas,nomor',
            'kec' => 'required|exists:districts,id',
            'password' => 'required|string|min:8|max:100',
            'confirm_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(null,$validator->errors(),422);
        };

        try{
            $inputPassword = $request->input('password');
            $semuaAkun = akun_puskesmas::all();

            foreach ($semuaAkun as $akun) {
                if (Hash::check($inputPassword, $akun->password)) {
                    return ResponseFormatter::error(null, [
                        'password' => ['Password sudah digunakan Akun lain']
                    ], 422);
                }
            }

            $hashPassword = Hash::make($request->password);

            $data = akun_puskesmas::create([
                'name' => $request->nama,
                'nomor' => $request->nomor,
                'id_district' => $request->kec,
                'password' => $hashPassword,
            ]);

            return ResponseFormatter::success($data,"Berhasil Menambah Data Akun Puskesmas!");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return ResponseFormatter::error($e->getMessage(), "Data gagal diproses. Kesalahan Server", 500);
        }
        return view('admin.akunPuskesmas');
    }

    public function changePassword($id, Request $request) {
        $validator = Validator::make($request->all(), [
            'nama' => 'string|max:100',
            'nomor' => 'string|regex:/^[0-9]{10,15}$/|unique:akun_puskesmas,nomor,' . $id,
            'kec' => 'exists:districts,id',
            'password' => 'string|min:8|max:100|nullable',
            'confirm_password' => 'same:password|nullable',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(null,$validator->errors(),422);
        };

        try{
            $hashPassword = Hash::make($request->password);
            $data = akun_puskesmas::find($id); 
            $updateData = [
                'name' => $request->nama,
                'nomor' => $request->nomor,
                'id_district' => $request->kec,
                'password' => $hashPassword,
            ];

            // Update data
            $data->update($updateData);

            return ResponseFormatter::success($data,"Berhasil Mengubah Data Akun Puskesmas!");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return ResponseFormatter::error($e->getMessage(), "Data gagal diproses. Kesalahan Server", 500);
        }
        return view('admin.akunPuskesmas');
    }

    // fitur data ibu
    public function daftar(Request $request){
        if($request->ajax()) {
            try {
                $data = User::where('role', 1)->with('village')->get();
        
                return ResponseFormatter::success($data, 'Berhasil Mendapatkan Data Ibu!');
            } catch (Exception $e) {
                Log::error($e->getMessage());
                return ResponseFormatter::error($e->getMessage(), "Data gagal diproses. Kesalahan Server", 500);
            }
        }
        return view("admin.ibu.daftarIbu");
    }

    public function graph($id, Request $request){
        try {
            $dataBB = User::where('id', $id)
                ->with(['berat_badan' => function($query) {
                    $query->orderBy('created_at', 'asc')  // Ambil dari terbaru
                        ->limit(9);                      // Ambil 9 data terakhir
                }])
                ->first();

            // Urutkan kembali dari lama ke baru untuk keperluan grafik
            $dataBB->berat_badan = $dataBB->berat_badan->sortBy('created_at')->values();

        } catch (Exception $e) {
            Log::error($e->getMessage());
            return ResponseFormatter::error($e->getMessage(), "Data gagal diproses. Kesalahan Server", 500);
        }
        // dd($dataBB);
        return view("admin.ibu.graphBB",['data' => $dataBB]);
    }

    // fitur data anak
    public function daftarAnak(Request $request) {
        if ($request->ajax()) {
            try {
                // Ambil data bayi beserta relasi user → village → district
                $data = bayi::with('user.village.district')->get();

                return ResponseFormatter::success($data, "Berhasil Mendapatkan Data Anak!");
            } catch (Exception $e) {
                Log::error($e->getMessage());
                return ResponseFormatter::error($e->getMessage(), "Data Gagal Diproses. Kesalahan Server", 500);
            }
        }
        return view("admin.anak.daftarAnak");
    }

    public function detaildGiziAnak($id, Request $request) {
        try {
            $histori = hist_gizi::where('id_bayi', $id)->orderBy('tanggal', 'asc')->get();

            $labels = [];
            $series = [];

            foreach ($histori as $item) {
                $labels[] = $item->tanggal;
                $series[] = json_decode($item->nilai_gizi);
            }

            return view("admin.anak.detailGiziAnak", [
                'labels' => $labels,
                'series' => $series
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return ResponseFormatter::error($e->getMessage(), "Data Gagal Diproses. Kesalahan Server", 500);
        }
    }

    public function detaildStuntingAnak($id, Request $request) {
        try {
            $histori = hist_stun::where('id_bayi', $id)->orderBy('tanggal', 'asc')->get();

            $labels = [];
            $series = [];

            foreach ($histori as $item) {
                $labels[] = $item->tanggal;
                $series[] = json_decode($item->jenis);
            }

            return view("admin.anak.detailStuntingAnak", [
                'labels' => $labels,
                'series' => $series
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return ResponseFormatter::error($e->getMessage(), "Data Gagal Diproses. Kesalahan Server", 500);
        }
    }

    public function daftarKecamatanGizi(Request $request) {
        if($request->ajax()) {
            try{
                $data = districts::where("regency_id",3511)->get();

                return ResponseFormatter::success($data, "Berhasil Mendapatkan Data Anak!");
            } catch (Exception $e) {
                Log::error($e->getMessage());
                return ResponseFormatter::error($e->getMessage(), "Data Gagal Diprossess. Kesalahan Server", 500);
            }
        }
        return view("admin.anak.giziKecamatan");
    }

    public function graphGiziAnak($id, Request $request){
        $data = DB::table('hist_gizi')
            ->join('bayi', 'hist_gizi.id_bayi', '=', 'bayi.id')
            ->join('users', 'bayi.id_users', '=', 'users.id')
            ->join('villages', 'users.id_villages', '=', 'villages.id')
            ->join('districts', 'villages.district_id', '=', 'districts.id')
            ->where('villages.district_id', $id) // atau '3511170' jika hardcoded
            ->select(
                'hist_gizi.tanggal',
                'hist_gizi.nilai_gizi',
                'hist_gizi.id_bayi',
                'bayi.id_users as user_id',
                'villages.name as village_name',
                'districts.name as district_name'
            )
            ->orderBy('hist_gizi.tanggal')
            ->get();


        $grouped = $data->groupBy('village_name')->map(function ($items, $village) {
            $total = [0, 0, 0, 0, 0];
            $count = 0;
            foreach ($items as $item) {
                $nilai = json_decode($item->nilai_gizi);
                foreach ($nilai as $i => $val) {
                    $total[$i] += $val;
                }
                $count++;
            }
            $avg = array_map(fn($t) => round($t / $count, 2), $total);
            return [
                'label' => $village,
                'data' => $avg,
            ];
        })->values();

        return view("admin.anak.grafikGizi", [
            'labels' => ['Makanan Pokok', 'Minuman', 'Sayuran', 'Buah', 'Lauk Pauk'],
            'datasets' => $grouped
        ]);
    }

    public function daftarDesaGizi($id, Request $request){
        if($request->ajax()) {
            try{
                $data = villages::where("district_id",$id)->get();

                return ResponseFormatter::success($data, "Berhasil Mendapatkan Data Anak!");
            } catch (Exception $e) {
                Log::error($e->getMessage());
                return ResponseFormatter::error($e->getMessage(), "Data Gagal Diprossess. Kesalahan Server", 500);
            }
        }
        return view("admin.anak.giziDesa",["id" => $id]);
    }

    public function eksporExcel($village_id, Request $request){
        return Excel::download(new ExportData($village_id), 'data-anak-desa.xlsx');
    }

    public function daftarKecamatanStunting(Request $request) {
        if($request->ajax()) {
            try{
                $data = districts::where("regency_id",3511)->get();

                return ResponseFormatter::success($data, "Berhasil Mendapatkan Data Anak!");
            } catch (Exception $e) {
                Log::error($e->getMessage());
                return ResponseFormatter::error($e->getMessage(), "Data Gagal Diprossess. Kesalahan Server", 500);
            }
        }
        return view("admin.anak.stuntingKecamatan");
    }

    public function graphStuntingAnak($district_id) {
        $data = DB::table('hist_stun')
            ->join('bayi', 'hist_stun.id_bayi', '=', 'bayi.id')
            ->join('users', 'bayi.id_users', '=', 'users.id')
            ->join('villages', 'users.id_villages', '=', 'villages.id')
            ->join('districts', 'villages.district_id', '=', 'districts.id')
            ->where('villages.district_id', $district_id) // gunakan string jika id bertipe char
            ->select(
                'hist_stun.tanggal',
                'hist_stun.jenis',
                'hist_stun.id_bayi',
                'bayi.id_users as user_id',
                'villages.name as village_name',
                'districts.name as district_name'
            )
            ->orderBy('hist_stun.tanggal')
            ->get();

        // Group data: desa -> tanggal -> [jenis values]
        $grouped = [];

        foreach ($data as $item) {
            $desa = $item->village_name;
            $tgl = date('d', strtotime($item->tanggal)); // tanggal saja (1-31)
            $jenis = (int)$item->jenis;

            if (!isset($grouped[$desa])) {
                $grouped[$desa] = [];
            }
            if (!isset($grouped[$desa][$tgl])) {
                $grouped[$desa][$tgl] = [];
            }

            $grouped[$desa][$tgl][] = $jenis;
        }

        // Hitung rata-rata jenis per tanggal per desa
        $labels = []; // tanggal unik
        $datasets = [];

        // Kumpulkan semua tanggal unik untuk labels
        foreach ($grouped as $desa => $tgls) {
            foreach ($tgls as $tgl => $jenisArr) {
                if (!in_array($tgl, $labels)) {
                    $labels[] = $tgl;
                }
            }
        }

        sort($labels, SORT_NUMERIC);

        // Build datasets
        foreach ($grouped as $desa => $tgls) {
            $dataPerTanggal = [];
            foreach ($labels as $tgl) {
                if (isset($tgls[$tgl])) {
                    $avg = array_sum($tgls[$tgl]) / count($tgls[$tgl]);
                    $dataPerTanggal[] = round($avg, 2);
                } else {
                    $dataPerTanggal[] = null; // tidak ada data di tanggal itu
                }
            }
            $datasets[] = [
                'label' => $desa,
                'data' => $dataPerTanggal
            ];
        }

        return view('admin.anak.grafikStunting', [
            'labels' => $labels,
            'datasets' => $datasets
        ]);
    }

    public function daftarDesaStunting($id, Request $request){
        if($request->ajax()) {
            try{
                $data = villages::where("district_id",$id)->get();

                return ResponseFormatter::success($data, "Berhasil Mendapatkan Data Anak!");
            } catch (Exception $e) {
                Log::error($e->getMessage());
                return ResponseFormatter::error($e->getMessage(), "Data Gagal Diprossess. Kesalahan Server", 500);
            }
        }
        return view("admin.anak.stuntingDesa",["id" => $id]);
    }


}
