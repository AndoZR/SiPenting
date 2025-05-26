<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Faker\Factory as Faker;

class bayi extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nowDate = date('Y-m-d');
        for($i = 0; $i <= 68; $i++) {
            DB::table('bayi')->insert([
                [
                    'nama' => 'Balita' . $i,
                    'tanggalLahir' => "2024-01-01",
                    'kelamin' => "L",
                    'id_users' => 4 + $i,
                ],
            ]);
        }
    }
}
