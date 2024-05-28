<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class makanan extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('makanan')->insert([
            [
                'nama' => 'Nasi',
                'id_jenis_gizi' => 1,
            ],
        ]);
        DB::table('makanan')->insert([
            [
                'nama' => 'Roti',
                'id_jenis_gizi' => 1,
            ],
        ]);
        DB::table('makanan')->insert([
            [
                'nama' => 'Ikan',
                'id_jenis_gizi' => 2,
            ],
        ]);
        DB::table('makanan')->insert([
            [
                'nama' => 'Ayam',
                'id_jenis_gizi' => 2,
            ],
        ]);
        DB::table('makanan')->insert([
            [
                'nama' => 'Sayur Bayam',
                'id_jenis_gizi' => 3,
            ],
        ]);
        DB::table('makanan')->insert([
            [
                'nama' => 'Sayur Sop',
                'id_jenis_gizi' => 3,
            ],
        ]);
        DB::table('makanan')->insert([
            [
                'nama' => 'Apel',
                'id_jenis_gizi' => 4,
            ],
        ]);
        DB::table('makanan')->insert([
            [
                'nama' => 'Pisang',
                'id_jenis_gizi' => 4,
            ],
        ]);
        DB::table('makanan')->insert([
            [
                'nama' => 'Air Putih',
                'id_jenis_gizi' => 5,
            ],
        ]);
    }
}
