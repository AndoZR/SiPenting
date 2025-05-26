<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class histGizi extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    $tanggalArray = [];

    // Buat array tanggal untuk 7 hari terakhir (termasuk hari ini)
    for ($i = 0; $i < 7; $i++) {
        $tanggalArray[] = now()->subDays($i)->toDateString();
    }

    // Untuk setiap bayi
    foreach (range(1, 69) as $idBayi) {
        foreach ($tanggalArray as $tanggal) {
            DB::table('hist_gizi')->insert([
                'tanggal' => $tanggal,
                'nilai_gizi' => json_encode([
                    rand(1, 3), // Makanan Pokok
                    rand(1, 3), // Lauk Pauk
                    rand(1, 3), // Sayur-sayuran
                    rand(1, 3), // Buah-buahan
                    rand(1, 3), // Minuman
                ]),
                'id_bayi' => $idBayi,
            ]);
        }
    }
    }

}
