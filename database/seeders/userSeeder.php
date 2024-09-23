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
        // inser user
        $faker = Faker::create();
        $nowDate = date('Y-m-d');
        for($i = 0; $i <= 3; $i++) {
            DB::table('users')->insert([
                [
                    'nik' => str_pad((3509200904020000 + $i), 16, '0', STR_PAD_LEFT), // Pastikan panjang 16 karakter
                    'username' => str_pad((3509200904020000 + $i), 16, '0', STR_PAD_LEFT),
                    'tanggalLahir' => "1995-01-01",
                    'namaIbu' => "User" . $i,
                    'bbPraHamil' => 50.5,
                    'tinggiBadan' => 160,
                    'role' => 1,
                    'id_villages' => 1101010001,
                    'id_subs' => "5a2419c2-03c3-4dac-9060-132986ab3818",
                    'password' => hash::make(3509200904020000 + $i),
                    'created_at' => $nowDate,
                ],
            ]);
        }

        DB::table('users')->insert([
            [
                'nik' => str_pad((3509200904020021), 16, '0', STR_PAD_LEFT),
                'username' => str_pad((3509200904020021), 16, '0', STR_PAD_LEFT),
                'tanggalLahir' => "2000-01-01",
                'namaIbu' => null,
                'bbPraHamil' => null,
                'tinggiBadan' => null,
                'role' => 2,
                'id_villages' => 1101010001,
                'password' => hash::make('3509200904020021'),
                'created_at' => $nowDate,
            ],
        ]);


        // bapeda
        DB::table('akun_bapeda')->insert([
            [
                'username' => "bapeda_admin",
                'name' => "admin",
                'password' => hash::make('bapeda_admin'),
                'created_at' => $nowDate,
            ],
        ]);

        // Dinkes
        DB::table('akun_dinkes')->insert([
            [
                'username' => "dinkes_admin",
                'name' => "admin",
                'password' => hash::make('dinkes_admin'),
                'created_at' => $nowDate,
            ],
        ]);

        // Puskesmas
        DB::table('akun_puskesmas')->insert([
            [
                'username' => "puskesmas_admin",
                'name' => "admin",
                'password' => hash::make('puskesmas_admin'),
                'created_at' => $nowDate,
            ],
        ]);

        // bidan
        DB::table('akun_bidan')->insert([
            [
                'username' => "bidan_admin",
                'name' => "admin",
                'password' => hash::make('bidan_admin'),
                'created_at' => $nowDate,
            ],
        ]);
    }
}
