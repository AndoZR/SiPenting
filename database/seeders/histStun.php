<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class histStun extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void{
        $bulanArray = [
            now()->startOfMonth(),                 // Mei
            now()->subMonth()->startOfMonth(),    // April
            now()->subMonths(2)->startOfMonth()   // Maret
        ];

        foreach ($bulanArray as $startOfMonth) {
            $tanggalArray = [];

            // Ambil tanggal 1 sampai 7 untuk bulan tersebut
            for ($i = 0; $i < 7; $i++) {
                $tanggalArray[] = $startOfMonth->copy()->addDays($i)->toDateString();
            }

            // Untuk setiap bayi
            foreach (range(1, 207) as $idBayi) {
                foreach ($tanggalArray as $tanggal) {
                    DB::table('hist_stun')->insert([
                        'tanggal' => $tanggal,
                        'jenis' => rand(1, 4), // 1: sangat pendek, 2: pendek, 3: normal, 4: tinggi
                        'id_bayi' => $idBayi,
                    ]);
                }
            }
        }
    }


}
