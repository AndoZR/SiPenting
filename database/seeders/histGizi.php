<?php

namespace Database\Seeders;

use App\Models\bayi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class histGizi extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void{
      $bayiIds = Bayi::pluck('id')->toArray();
        $insertData = [];

        // Loop 12 bulan ke belakang dari bulan ini
        for ($m = 0; $m < 12; $m++) {
            $startOfMonth = now()->subMonths($m)->startOfMonth();

            // Ambil tanggal 1–7 untuk bulan tersebut
            $tanggalArray = [];
            for ($i = 0; $i < 7; $i++) {
                $tanggalArray[] = $startOfMonth->copy()->addDays($i)->toDateString();
            }

            // Untuk setiap bayi, buat data gizi
            foreach ($bayiIds as $idBayi) {
                foreach ($tanggalArray as $tanggal) {
                    $insertData[] = [
                        'tanggal' => $tanggal,
                        'nilai_gizi' => json_encode([
                            rand(1, 3), // Makanan Pokok
                            rand(1, 3), // Lauk Pauk
                            rand(1, 3), // Sayur-sayuran
                            rand(1, 3), // Buah-buahan
                            rand(1, 3), // Minuman
                        ]),
                        'id_bayi' => $idBayi,
                    ];
                }
            }
        }

        // Insert ke DB dalam batch agar efisien
        foreach (array_chunk($insertData, 1000) as $chunk) {
            DB::table('hist_gizi')->insert($chunk);
        }
    }
}
