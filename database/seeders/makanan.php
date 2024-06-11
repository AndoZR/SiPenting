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
                'nama' => 'Bubur Komplit',
                'gambar' => 'buburKomplit.jpg',
            ],
        ]);
        DB::table('makanan')->insert([
            [
                'nama' => 'Air Mineral',
                'gambar' => 'air.jpg',
            ],
        ]);
        DB::table('makanan')->insert([
            [
                'nama' => 'Lauk Pauk',
                'gambar' => 'lauk.jpg',
            ],
        ]);
        DB::table('makanan')->insert([
            [
                'nama' => 'nasi',
                'gambar' => 'nasi.jpg',
            ],
        ]);
        DB::table('makanan')->insert([
            [
                'nama' => 'Sayur-Sayuran',
                'gambar' => 'sayur.jpeg',
            ],
        ]);
        DB::table('makanan')->insert([
            [
                'nama' => 'Buah-Buahan',
                'gambar' => 'buah.jpg',
            ],
        ]);
    }
}
