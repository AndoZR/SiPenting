<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $nowDate = date('Y-m-d');
        for($i = 0; $i <= 3; $i++) {
            DB::table('users')->insert([
                [
                    'nik' => 350920090402000 + $i,
                    'username' => 350920090402000 + $i,
                    'tanggalLahir' => "1995-01-01",
                    'namaIbu' => "User" . $i,
                    'tanggalLahirBayi' => "2023-05-01",
                    'bbPraHamil' => 50.5,
                    'tinggiBadan' => 160,
                    'role' => 1,
                    'password' => hash::make('123123123'),
                    'created_at' => $nowDate,
                ],
            ]);
        }
    }
}
