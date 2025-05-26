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
    public function run(): void
    {
        // Generate 7 hari terakhir, dari hari ini sampai 6 hari ke belakang
        $tanggalArray = [];
        for ($i = 0; $i < 7; $i++) {
            $tanggalArray[] = now()->copy()->subDays($i)->toDateString(); // gunakan copy() agar tidak mengubah objek awal
        }

        foreach (range(1, 69) as $idBayi) {
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
