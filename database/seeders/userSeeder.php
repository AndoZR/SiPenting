<?php

namespace Database\Seeders;

use App\Models\berat_badan;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // user percobaan developer
        // $faker = Faker::create();
        // USER BIASA DEVELOPER
        $nowDate = date('Y-m-d');
        for($i = 0; $i <= 1; $i++) {
            DB::table('users')->insert([
                [
                    'nik' => str_pad((3511111111111111 + $i), 16, '0', STR_PAD_LEFT), // Pastikan panjang 16 karakter
                    'username' => str_pad((3511111111111111 + $i), 16, '0', STR_PAD_LEFT),
                    'tanggalLahir' => "1995-01-01",
                    'namaIbu' => "UserDEVELOPER" . $i,
                    'bbPraHamil' => 50.5,
                    'tinggiBadan' => 160,
                    'role' => 1,
                    'id_villages' => 1101010001,
                    'id_subs' => "5a2419c2-03c3-4dac-9060-132986ab3818",
                    'password' => hash::make(3511111111111111 + $i),
                    'created_at' => $nowDate,
                ],
            ]);
        }

        // BIDAN DEVELOPER
        DB::table('users')->insert([
            [
                'nik' => str_pad((3509200904020021), 16, '0', STR_PAD_LEFT),
                'username' => str_pad((3509200904020021), 16, '0', STR_PAD_LEFT),
                'tanggalLahir' => "2000-01-01",
                'namaIbu' => "BidanDEVELOPER",
                'bbPraHamil' => null,
                'tinggiBadan' => null,
                'role' => 2,
                'id_villages' => 1101010001,
                'password' => hash::make('3509200904020021'),
                'created_at' => $nowDate,
            ],
        ]);


        // bapeda user
        DB::table('akun_bapeda')->insert([
            [
                'username' => "bapeda_admin",
                'email' => "bapeda@gmail.com",
                'password' => hash::make('bapeda_admin'),
                'created_at' => $nowDate,
            ],
        ]);

        // Puskesmas user
        DB::table('akun_puskesmas')->insert([
            [
                'username' => "puskesmas_admin",
                'name' => "Puskesmas",
                'password' => hash::make('puskesmas_admin'),
                'created_at' => $nowDate,
            ],
        ]);

        //Inject All NPC
        $villages = [
            3511010001,3511010002,3511010003,
            3511020001,3511020002,3511020003,
            3511030001,3511030002,3511030004,
            3511031002,3511031003,3511031004,
            3511040001,3511040002,3511040003,
            3511050001,3511050002,3511050003,
            3511060001,3511060006,3511060007,
            3511061001,3511061002,3511061003,
            3511070001,3511070002,3511070003,
            3511080001,3511080002,3511080004,
            3511090001,3511090002,3511090003,
            3511100001,3511100002,3511100003,
            3511110001,3511110002,3511110003,
            3511111001,3511111002,3511111003,
            3511120001,3511120002,3511120003,
            3511130001,3511130002,3511130003,
            3511140001,3511140002,3511140003,
            3511141001,3511141002,3511141003,
            3511150005,3511150006,3511150007,
            3511151001,3511151002,3511151003,
            3511152001,3511152002,3511152003,
            3511160002,3511160003,3511160004,
            3511170001,3511170002,3511170003,
        ];

        // Insert user untuk setiap id_village
        foreach ($villages as $i => $villageId) {
            DB::table('users')->insert([
                'nik' => str_pad((3511222222222222 + $i), 16, '0', STR_PAD_LEFT),
                'username' => str_pad((3511222222222222 + $i), 16, '0', STR_PAD_LEFT),
                'tanggalLahir' => "1995-01-01",
                'namaIbu' => "User" . $i,
                'bbPraHamil' => 50.5,
                'tinggiBadan' => 160,
                'role' => 1,
                'id_villages' => $villageId,
                'id_subs' => "5a2419c2-03c3-4dac-9060-132986ab3818",
                'password' => Hash::make(3511222222222222 + $i),
                'created_at' => $nowDate,
            ]);
        }

        // SEEDER UNTUK BERAT BADAN IBU HAMIL HISTORY
        $userCount = 72; // total id_users
                $now = Carbon::now();

                for ($userId = 1; $userId <= $userCount; $userId++) {
                    for ($i = 0; $i < 9; $i++) {
                        $date = $now->copy()->subMonths($i)->startOfMonth();

                        berat_badan::create([
                            'bbNow' => rand(8, 20), // nilai berat badan acak antara 8-20 kg
                            'id_users' => $userId,
                            'created_at' => $date,
                            'updated_at' => $date
                        ]);
                    }
                }
    }
}