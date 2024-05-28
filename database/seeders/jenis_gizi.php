<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class jenis_gizi extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nowDate = date('Y-m-d');
        DB::table('jenis_gizi')->insert([
            [
                'jenis' => 'Makanan Pokok',
                'bobot' => 1,
            ],
        ]);
        DB::table('jenis_gizi')->insert([
            [
                'jenis' => 'Lauk Pauk',
                'bobot' => 1,
            ],
        ]);
        DB::table('jenis_gizi')->insert([
            [
                'jenis' => 'Sayur',
                'bobot' => 1,
            ],
        ]);
        DB::table('jenis_gizi')->insert([
            [
                'jenis' => 'Buah-Buahan',
                'bobot' => 1,
            ],
        ]);
        DB::table('jenis_gizi')->insert([
            [
                'jenis' => '6-8 Gelas/Hari',
                'bobot' => 1,
            ],
        ]);
    }
}
