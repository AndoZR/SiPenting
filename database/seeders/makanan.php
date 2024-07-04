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
                'nama' => 'Air Mineral',
                'gambar' => 'air.jpg',
            ],
        ]);
        DB::table('makanan')->insert([
            [
                'nama' => 'Lauk Pauk(Ayam, Telur, Ikan, dll)',
                'gambar' => 'lauk.jpg',
            ],
        ]);
        DB::table('makanan')->insert([
            [
                'nama' => 'Makanan Pokok (Nasi, Kentang, Singkong, dll)',
                'gambar' => 'nasi.jpg',
            ],
        ]);
        DB::table('makanan')->insert([
            [
                'nama' => 'Sayur-Sayuran (Bayam, Wortel, Brokoli, dll',
                'gambar' => 'sayur.jpeg',
            ],
        ]);
        DB::table('makanan')->insert([
            [
                'nama' => 'Buah-Buahan (Apel, Jeruk, Pisang, dll)',
                'gambar' => 'buah.jpg',
            ],
        ]);
    }
}
