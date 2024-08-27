<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class artikelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('artikel')->insert([
            [
                'judul' => 'Judul A',
                'deskripsi' => 'Ini adalah deskripsi dari Judul A',
                'gambar' => "gambar1",
                'created_at' => date('Y-m-d'),
            ],
        ]);
        DB::table('artikel')->insert([
            [
                'judul' => 'Judul B',
                'deskripsi' => 'Ini adalah deskripsi dari Judul B',
                'gambar' => "gambar2",
                'created_at' => date('Y-m-d'),
            ],
        ]);
        DB::table('artikel')->insert([
            [
                'judul' => 'Judul C',
                'deskripsi' => 'Ini adalah deskripsi dari Judul C',
                'gambar' => "gambar3",
                'created_at' => date('Y-m-d'),
            ],
        ]);
    }
}
