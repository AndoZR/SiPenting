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

        // Misalnya user dengan ID 4 sampai 72 (69 user)
        for ($userId = 4; $userId <= 72; $userId++) {
            for ($j = 1; $j <= 3; $j++) {
                DB::table('bayi')->insert([
                    'nama' => 'Balita ' . $userId . '-' . $j,
                    'tanggalLahir' => '2024-01-01',
                    'kelamin' => 'L',
                    'id_users' => $userId,
                ]);
            }
        }

    }
}
