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
                'nama' => 'Makanan Pokok (Nasi, Kentang, Singkong, dll)',
                'gambar' => 'nasi.jpg',
                'deskripsi' => '1 sdm setara dengan 14 gr',
                'satuan' => 'SDM',
            ],
        ]);
        DB::table('makanan')->insert([
            [
                'nama' => 'Lauk Pauk(Ayam, Telur, Ikan, dll)',
                'gambar' => 'lauk.jpg',
                'deskripsi' => '1 sdm setara dengan 14 gr',
                'satuan' => 'SDM',
            ],
        ]);
        DB::table('makanan')->insert([
            [
                'nama' => 'Sayur-Sayuran (Bayam, Wortel, Brokoli, dll',
                'gambar' => 'sayur.jpeg',
                'deskripsi' => '1 sdm setara dengan 14 gr',
                'satuan' => 'SDM',
            ],
        ]);
        DB::table('makanan')->insert([
            [
                'nama' => 'Buah-Buahan (Apel, Jeruk, Pisang, dll)',
                'gambar' => 'buah.jpg',
                'deskripsi' => '1 sdm setara dengan 14 gr',
                'satuan' => 'SDM',
            ],
        ]);
        DB::table('makanan')->insert([
            [
                'nama' => 'Cairan (Air, Susu, dll)',
                'gambar' => 'air.jpg',
                'deskripsi' => '1 gelas setara dengan 250 ml',
                'satuan' => 'gelas',
            ],
        ]);
    }
}
