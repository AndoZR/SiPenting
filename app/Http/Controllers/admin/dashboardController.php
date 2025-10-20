<?php

namespace App\Http\Controllers\admin;

use Exception;
use Carbon\Carbon;
use App\Models\bayi;
use App\Models\User;
use App\Models\villages;
use App\Models\districts;
use App\Models\hist_gizi;
use App\Models\hist_stun;
use App\Exports\ExportData;
use Illuminate\Http\Request;
use App\Models\akun_puskesmas;
use App\Helpers\ResponseFormatter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class dashboardController extends Controller
{
    public function index(){
        // Total seluruh pengguna
        $jumlahPengguna = User::count();

        // Total user dengan NIK yang diawali '3511'
        $jumlahPenggunaNik3511 = User::where('nik', 'like', '3511%')->count();

        // Total user dengan NIK bukan '3511'
        $jumlahPenggunaNon3511 = User::where('nik', 'not like', '3511%')->count();

        // Kirim semua data ke view
        return view('admin.home', compact(
            'jumlahPengguna',
            'jumlahPenggunaNik3511',
            'jumlahPenggunaNon3511'
        ));
    }

    public function gantiNomorPuskesmas(Request $request){
        $id = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'nomor' => 'string|regex:/^[0-9]{10,15}$/|unique:akun_puskesmas,nomor,' . $id,
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(null,$validator->errors(),422);
        };

        try{
            $hashPassword = Hash::make($request->password);
            $data = akun_puskesmas::find($id); 
            $updateData = [
                'nomor' => $request->nomor,
            ];

            // Update data
            $data->update($updateData);

            return ResponseFormatter::success($data,"Berhasil Mengubah Data Akun Puskesmas!");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return ResponseFormatter::error($e->getMessage(), "Data gagal diproses. Kesalahan Server", 500);
        }
        return view('admin.home');
    }

    public function viewAkunPuskesmas(Request $request){
        if($request->ajax()) {
            try{
                // $dataAkun = akun_puskesmas::with('districts')->get();
                $dataAkun = akun_puskesmas::with(['districts', 'villages'])->get();

                return ResponseFormatter::success($dataAkun,"Berhasil Mendapatkan Data Akun!");
            } catch (Exception $e) {
                Log::error($e->getMessage());
                return ResponseFormatter::error($e->getMessage(), "Data gagal diproses. Kesalahan Server", 500);
            }
        }
        $dataKecamatan = districts::where("regency_id",3511)->get();
        $dataDesaBondowoso = villages::where("district_id",3511100)->get();
        return view('admin.akunPuskesmas', ["dataKecamatan" => $dataKecamatan,"dataDesaBondowoso" => $dataDesaBondowoso]);
    }

    public function addPuskesmas(Request $request) {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:100',
            'nomor' => 'required|string|regex:/^[0-9]{10,15}$/|unique:akun_puskesmas,nomor',
            'kec' => 'required|exists:districts,id',
            'password' => 'required|string|min:8|max:100',
            'confirm_password' => 'required|same:password',
        ]);

        // Jika kecamatan Bondowoso (3511100), tambahkan validasi desa
        if ($request->kec == '3511100') {
            $validator->after(function ($validator) use ($request) {
                if (empty($request->desa) || !is_array($request->desa)) {
                    $validator->errors()->add('desa', 'Minimal pilih satu desa.');
                }
            });
        }

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

            if ($request->kec == '3511100') {
                // Cek apakah ada desa yang sudah dipakai oleh Puskesmas lain
                $desaTerpakai = DB::table('pivot_puskesmas_village')
                    ->whereIn('village_id', $request->desa)
                    ->pluck('village_id')
                    ->toArray();

                if (!empty($desaTerpakai)) {
                    $namaDesaTerpakai = DB::table('villages')
                        ->whereIn('id', $desaTerpakai)
                        ->pluck('name')
                        ->toArray();

                    return ResponseFormatter::error(null, [
                        'desa' => ['Desa berikut sudah digunakan oleh Puskesmas lain: ' . implode(', ', $namaDesaTerpakai)]
                    ], 422);
                }

                // Insert desa baru
                foreach ($request->desa as $village_id) {
                    DB::table('pivot_puskesmas_village')->insert([
                        'puskesmas_id' => $data->id,
                        'village_id' => $village_id
                    ]);
                }
            }


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

        // Jika kecamatan Bondowoso (3511100), tambahkan validasi desa
        if ($request->kec == '3511100') {
            $validator->after(function ($validator) use ($request) {
                if (empty($request->desa) || !is_array($request->desa)) {
                    $validator->errors()->add('desa', 'Minimal pilih satu desa.');
                }
            });
        }

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

            // Update desa pivot table
            if ($request->kec == '3511100') {
                // Cek apakah ada desa yang sudah dipakai oleh Puskesmas lain
                $desaTerpakai = DB::table('pivot_puskesmas_village')
                    ->whereIn('village_id', $request->desa)
                    ->where('puskesmas_id', '!=', $id)
                    ->pluck('village_id')
                    ->toArray();

                if (!empty($desaTerpakai)) {
                    return ResponseFormatter::error(null, [
                        'desa' => ['Beberapa desa sudah digunakan oleh Puskesmas lain.']
                    ], 422);
                }

                // Hapus desa lama dulu
                DB::table('pivot_puskesmas_village')->where('puskesmas_id', $id)->delete();

                // Insert desa baru
                foreach ($request->desa as $village_id) {
                    DB::table('pivot_puskesmas_village')->insert([
                        'puskesmas_id' => $id,
                        'village_id' => $village_id
                    ]);
                }
            } else {
                // Jika kecamatan bukan 3511100, hapus desa pivot yang mungkin ada
                DB::table('pivot_puskesmas_village')->where('puskesmas_id', $id)->delete();
            }


            return ResponseFormatter::success($data,"Berhasil Mengubah Data Akun Puskesmas!");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return ResponseFormatter::error($e->getMessage(), "Data gagal diproses. Kesalahan Server", 500);
        }
        return view('admin.akunPuskesmas');
    }

    public function hapusAkunPuskesmas($id) {
        try{
            $data = akun_puskesmas::find($id);
            $data->delete();
            return ResponseFormatter::success("Data Artikel Berhasil Dihapus!");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return ResponseFormatter::error($e->getMessage(), "Data gagal dihapus. Kesalahan Server", 500);
        }
    }

    // fitur data ibu
    public function daftar(Request $request){
        if($request->ajax()) {
            try {
                // Deteksi siapa yang login
                if (auth('bapeda')->check()) {
                    $data = User::where('role', 1)->with('village')->get(); // Akses penuh untuk Bapeda
                } elseif (auth('puskesmas')->check()) {
                    $puskesmas = auth('puskesmas')->user();

                    $desaIds = villages::where('district_id', $puskesmas->id_district)->pluck('id');
                    $data = User::where('role', 1)
                        ->whereIn('id_villages', $desaIds)
                        ->with('village')
                        ->get();
                } else {
                    return ResponseFormatter::error(null, 'Unauthorized', 401);
                }
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
                $data = collect();

                if (auth('bapeda')->check()) {
                    // Jika login sebagai bapeda, ambil semua anak
                    $data = Bayi::with('user.village.district')->get();
                } elseif (auth('puskesmas')->check()) {
                    // Jika login sebagai puskesmas, ambil anak berdasarkan desa dalam kecamatan yang terkait
                    $puskesmas = auth('puskesmas')->user();
                    $desaIds = Villages::where('district_id', $puskesmas->id_district)->pluck('id');

                    $data = Bayi::whereHas('user', function ($query) use ($desaIds) {
                        $query->whereIn('id_villages', $desaIds);
                    })->with('user.village.district')->get();
                }

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

    // GIZI DAERAH
    public function daftarKecamatanGizi(Request $request) {
        if($request->ajax()) {
            try{
                if (auth('puskesmas')->check()) {
                    $user = auth('puskesmas')->user();
                    $data = Districts::where('id', $user->id_district)->get(); // hanya kecamatan puskesmas itu
                } elseif (auth('bapeda')->check()) {
                    $data = Districts::where("regency_id", 3511)->get(); // semua kecamatan
                }

                return ResponseFormatter::success($data, "Berhasil Mendapatkan Data Anak!");
            } catch (Exception $e) {
                Log::error($e->getMessage());
                return ResponseFormatter::error($e->getMessage(), "Data Gagal Diprossess. Kesalahan Server", 500);
            }
        }
        return view("admin.anak.giziKecamatan");
    }

    public function eksporExcelKecamatan($kecamatan_id) {
        // Export berdasarkan kecamatan
        $nama = districts::find($kecamatan_id)?->name;
        return Excel::download(new ExportData($kecamatan_id, 'kecamatan', 'gizi'), 'data-gizi-kecamatan-' . $nama . '.xlsx');
    }

    public function graphGiziAnak($id, Request $request){
        $data = DB::table('hist_gizi')
            ->join('bayi', 'hist_gizi.id_bayi', '=', 'bayi.id')
            ->join('users', 'bayi.id_users', '=', 'users.id')
            ->join('villages', 'users.id_villages', '=', 'villages.id')
            ->join('districts', 'villages.district_id', '=', 'districts.id')
            ->where('villages.district_id', $id)
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

    public function eksporExcelDesa($village_id, Request $request)
    {
        $nama = villages::find($village_id)?->name;
        return Excel::download(new ExportData($village_id, 'desa', 'gizi'), 'data-gizi-desa-' . $nama . '.xlsx');
    }

    // STUNTING DAERAH
    public function daftarKecamatanStunting(Request $request) {
        if($request->ajax()) {
            try{
                if (auth('puskesmas')->check()) {
                    $user = auth('puskesmas')->user();
                    $data = Districts::where('id', $user->id_district)->get();
                } elseif (auth('bapeda')->check()) {
                    $data = Districts::where("regency_id", 3511)->get();
                }
                return ResponseFormatter::success($data, "Berhasil Mendapatkan Data Anak!");
            } catch (Exception $e) {
                Log::error($e->getMessage());
                return ResponseFormatter::error($e->getMessage(), "Data Gagal Diprossess. Kesalahan Server", 500);
            }
        }
        return view("admin.anak.stuntingKecamatan");
    }

    public function eksporExcelStuntKecamatan($kecamatan_id) {
        // Export berdasarkan kecamatan
        $nama = districts::find($kecamatan_id)?->name;
        return Excel::download(new ExportData($kecamatan_id, 'kecamatan', 'stunting'), 'data-stunting-kecamatan-' . $nama . '.xlsx');
    }

public function graphStuntingAnak($district_id)
{
    // Ambil tanggal awal (5 bulan ke belakang dari sekarang)
    $startDate = Carbon::now()->subMonths(5)->startOfMonth();
    $endDate = Carbon::now()->endOfMonth();

    // Ambil data dari database
    $data = DB::table('hist_stun')
        ->join('bayi', 'hist_stun.id_bayi', '=', 'bayi.id')
        ->join('users', 'bayi.id_users', '=', 'users.id')
        ->join('villages', 'users.id_villages', '=', 'villages.id')
        ->join('districts', 'villages.district_id', '=', 'districts.id')
        ->where('villages.district_id', $district_id)
        ->whereBetween('hist_stun.tanggal', [$startDate, $endDate]) // ✅ hanya ambil 5 bulan terakhir
        ->select(
            'hist_stun.tanggal',
            'hist_stun.jenis',
            'villages.name as village_name',
            'districts.name as district_name'
        )
        ->orderBy('hist_stun.tanggal')
        ->get();

    // Group data: desa -> bulan -> [jenis values]
    $grouped = [];

    foreach ($data as $item) {
        $desa = $item->village_name;
        $bulan = date('Y-m', strtotime($item->tanggal)); // format misal "2025-05"
        $jenis = (int)$item->jenis;

        if (!isset($grouped[$desa])) {
            $grouped[$desa] = [];
        }
        if (!isset($grouped[$desa][$bulan])) {
            $grouped[$desa][$bulan] = [];
        }

        $grouped[$desa][$bulan][] = $jenis;
    }

    // Ambil semua label bulan unik (misal: 2025-05, 2025-06, dst)
    $labels = [];

    foreach ($grouped as $desa => $bulanData) {
        foreach ($bulanData as $bulan => $jenisArr) {
            if (!in_array($bulan, $labels)) {
                $labels[] = $bulan;
            }
        }
    }

    sort($labels); // urutkan bulan dari lama ke baru

    // Buat datasets per desa
    $datasets = [];

    foreach ($grouped as $desa => $bulanData) {
        $dataPerBulan = [];
        foreach ($labels as $bulan) {
            if (isset($bulanData[$bulan])) {
                $avg = array_sum($bulanData[$bulan]) / count($bulanData[$bulan]);
                $dataPerBulan[] = round($avg, 2);
            } else {
                $dataPerBulan[] = null;
            }
        }
        $datasets[] = [
            'label' => $desa,
            'data' => $dataPerBulan
        ];
    }

    return view('admin.anak.grafikStunting', [
        'labels' => $labels,
        'datasets' => $datasets
    ]);
}


    // public function graphStuntingAnak($district_id) {
    //     $data = DB::table('hist_stun')
    //         ->join('bayi', 'hist_stun.id_bayi', '=', 'bayi.id')
    //         ->join('users', 'bayi.id_users', '=', 'users.id')
    //         ->join('villages', 'users.id_villages', '=', 'villages.id')
    //         ->join('districts', 'villages.district_id', '=', 'districts.id')
    //         ->where('villages.district_id', $district_id) // gunakan string jika id bertipe char
    //         ->select(
    //             'hist_stun.tanggal',
    //             'hist_stun.jenis',
    //             'hist_stun.id_bayi',
    //             'bayi.id_users as user_id',
    //             'villages.name as village_name',
    //             'districts.name as district_name'
    //         )
    //         ->orderBy('hist_stun.tanggal')
    //         ->get();

    //     // Group data: desa -> tanggal -> [jenis values]
    //     $grouped = [];

    //     foreach ($data as $item) {
    //         $desa = $item->village_name;
    //         $tgl = date('d', strtotime($item->tanggal)); // tanggal saja (1-31)
    //         $jenis = (int)$item->jenis;

    //         if (!isset($grouped[$desa])) {
    //             $grouped[$desa] = [];
    //         }
    //         if (!isset($grouped[$desa][$tgl])) {
    //             $grouped[$desa][$tgl] = [];
    //         }

    //         $grouped[$desa][$tgl][] = $jenis;
    //     }

    //     // Hitung rata-rata jenis per tanggal per desa
    //     $labels = []; // tanggal unik
    //     $datasets = [];

    //     // Kumpulkan semua tanggal unik untuk labels
    //     foreach ($grouped as $desa => $tgls) {
    //         foreach ($tgls as $tgl => $jenisArr) {
    //             if (!in_array($tgl, $labels)) {
    //                 $labels[] = $tgl;
    //             }
    //         }
    //     }

    //     sort($labels, SORT_NUMERIC);

    //     // Build datasets
    //     foreach ($grouped as $desa => $tgls) {
    //         $dataPerTanggal = [];
    //         foreach ($labels as $tgl) {
    //             if (isset($tgls[$tgl])) {
    //                 $avg = array_sum($tgls[$tgl]) / count($tgls[$tgl]);
    //                 $dataPerTanggal[] = round($avg, 2);
    //             } else {
    //                 $dataPerTanggal[] = null; // tidak ada data di tanggal itu
    //             }
    //         }
    //         $datasets[] = [
    //             'label' => $desa,
    //             'data' => $dataPerTanggal
    //         ];
    //     }

    //     // dd($datasets);

    //     return view('admin.anak.grafikStunting', [
    //         'labels' => $labels,
    //         'datasets' => $datasets
    //     ]);
    // }

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

    public function eksporExcelStuntDesa($village_id, Request $request)
    {
        $nama = villages::find($village_id)?->name;
        return Excel::download(new ExportData($village_id, 'desa', 'stunting'), 'data-stunting-desa-' . $nama . '.xlsx');
    }
}
