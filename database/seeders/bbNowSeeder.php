<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class bbNowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('berat_badan')->insert([
            [
                'bbNow' => '9',
                'id_users' => "1",
                'created_at' => "2023-11-15 15:31:18",
            ],
        ]);
        DB::table('berat_badan')->insert([
            [
                'bbNow' => '13',
                'id_users' => "1",
                'created_at' => "2023-12-15 15:31:18",
            ],
        ]);
        DB::table('berat_badan')->insert([
            [
                'bbNow' => '16',
                'id_users' => "1",
                'created_at' => "2024-01-15 15:31:18",
            ],
        ]);
        DB::table('berat_badan')->insert([
            [
                'bbNow' => '19',
                'id_users' => "1",
                'created_at' => "2024-02-15 15:31:18",
            ],
        ]);
        DB::table('berat_badan')->insert([
            [
                'bbNow' => '25',
                'id_users' => "1",
                'created_at' => "2024-03-15 15:31:18",
            ],
        ]);
        DB::table('berat_badan')->insert([
            [
                'bbNow' => '20',
                'id_users' => "1",
                'created_at' => "2024-04-15 15:31:18",
            ],
        ]);
        DB::table('berat_badan')->insert([
            [
                'bbNow' => '30',
                'id_users' => "1",
                'created_at' => "2024-05-15 15:31:18",
            ],
        ]);
        DB::table('berat_badan')->insert([
            [
                'bbNow' => '40',
                'id_users' => "1",
                'created_at' => "2024-06-15 15:31:18",
            ],
        ]);
        DB::table('berat_badan')->insert([
            [
                'bbNow' => '40',
                'id_users' => "1",
                'created_at' => "2024-07-15 15:31:18",
            ],
        ]);
        DB::table('berat_badan')->insert([
            [
                'bbNow' => '50',
                'id_users' => "1",
                'created_at' => "2024-08-15 15:31:18",
            ],
        ]);
        DB::table('berat_badan')->insert([
            [
                'bbNow' => '48',
                'id_users' => "1",
                'created_at' => "2024-09-15 15:31:18",
            ],
        ]);
    }
}
