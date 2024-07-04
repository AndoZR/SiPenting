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
                    'nik' => 3509200904020000 + $i,
                    'username' => 3509200904020000 + $i,
                    'tanggalLahir' => "1995-01-01",
                    'namaIbu' => "User" . $i,
                    'bbPraHamil' => 50.5,
                    'tinggiBadan' => 160,
                    'role' => 1,
                    'password' => hash::make(3509200904020000 + $i),
                    'created_at' => $nowDate,
                ],
            ]);
        }

        DB::table('users')->insert([
            [
                'nik' => 3509200904020021,
                'username' => 3509200904020021,
                'tanggalLahir' => "2000-01-01",
                'namaIbu' => null,
                'bbPraHamil' => null,
                'tinggiBadan' => null,
                'role' => 2,
                'password' => hash::make('3509200904020021'),
                'created_at' => $nowDate,
            ],
        ]);
    }
}
