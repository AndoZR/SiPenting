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
                'deskripsi' => 'Ini adalah deskripsi dari Judul A Ini adalah deskripsi dari Judul A Ini adalah deskripsi dari Judul A Ini adalah deskripsi dari Judul A',
                'gambar' => "gambar1",
                'url_video' => "https://www.youtube.com/watch?v=RH9zOxSGoHg",
                'created_at' => date('Y-m-d'),
            ],
        ]);
        DB::table('artikel')->insert([
            [
                'judul' => 'Judul B',
                'deskripsi' => 'Ini adalah deskripsi dari Judul B Ini adalah deskripsi dari Judul B Ini adalah deskripsi dari Judul B Ini adalah deskripsi dari Judul B',
                'gambar' => "gambar2",
                'url_video' => "https://www.youtube.com/watch?v=RH9zOxSGoHg",
                'created_at' => date('Y-m-d'),
            ],
        ]);
        DB::table('artikel')->insert([
            [
                'judul' => 'Judul C',
                'deskripsi' => 'Ini adalah deskripsi dari Judul C Ini adalah deskripsi dari Judul C Ini adalah deskripsi dari Judul C Ini adalah deskripsi dari Judul C',
                'gambar' => "gambar3",
                'url_video' => "",
                'created_at' => date('Y-m-d'),
            ],
        ]);
    }
}
