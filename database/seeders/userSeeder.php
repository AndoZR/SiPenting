<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use App\Models\berat_badan;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
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
        // UNTUK GUEST DI ANDRODI
        DB::table('users')->insert([
            [
                'nik' => str_pad((1919191919191919), 16, '0', STR_PAD_LEFT), // Pastikan panjang 16 karakter
                'username' => str_pad((1919191919191919), 16, '0', STR_PAD_LEFT),
                'tanggalLahir' => "1995-01-01",
                'namaIbu' => "UserDEVELOPERGUEST",
                'bbPraHamil' => 50.5,
                'tinggiBadan' => 160,
                'role' => 3,
                'id_villages' => 1101010001,
                'id_subs' => "none",
                'password' => hash::make(1919191919191919),
                'created_at' => $nowDate,
            ],
        ]);

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
                'name' => "bapeda_admin",
                'email' => "bapeda@gmail.com",
                'password' => hash::make('bapeda_admin'),
                'created_at' => $nowDate,
            ],
        ]);

        // Puskesmas user SEED
        $dataKecamatan = [
            '3511010','3511020',
            '3511030','3511031',
            '3511040','3511050',
            '3511060','3511061',
            '3511070','3511080',
            '3511090','3511170',
            '3511110','3511111',
            '3511120','3511130',
            '3511140','3511141',
            '3511150','3511151',
            '3511152','3511160',
            '3511100'
        ];

        for ($i = 1; $i <= 25; $i++) {
            DB::table('akun_puskesmas')->insert([
                [
                    'name' => "Puskesmas" . $i,
                    'nomor' => "08121212121" . $i,
                    'id_district' => $i >= 23 ? $dataKecamatan[22] : $dataKecamatan[$i - 1],
                    'password' => hash::make('puskesmas_admin' . $i),
                    'created_at' => $nowDate,
                ],
            ]);
        }

        $dataPivotDesa = [
            // puskesmas_id => [array desa ids]
            23 => [
                '3511100004', // Nangkaan
                '3511100007', // Badean
                '3511100002', // Sukowiryo
                '3511100001', // Pancoran
                '3511100003', // Kembang
            ],
            24 => [
                '3511100010', // Kademangan
                '3511100005', // Tamansari
                '3511100011', // Pejaten
            ],
            25 => [
                '3511100008', // Kotakulon
                '3511100006', // Dabasah
                '3511100009', // Blindungan
            ],
        ];

        foreach ($dataPivotDesa as $puskesmas_id => $villages) {
            foreach ($villages as $village_id) {
                DB::table('pivot_puskesmas_village')->insert([
                    'puskesmas_id' => $puskesmas_id,
                    'village_id' => $village_id,
                ]);
            }
        }

        //Inject All NPC
        $villages = [
            '3511010001','3511010002','3511010003',
            '3511020001','3511020002','3511020003',
            '3511030001','3511030002','3511030004',
            // Dihapus: 3511031002, 3511031003, 3511031004
            '3511040001','3511040002','3511040003',
            '3511050001','3511050002','3511050003',
            '3511060001','3511060006','3511060007',
            '3511061001','3511061002','3511061003',
            '3511070001','3511070002','3511070003',
            '3511080001','3511080002','3511080004',
            '3511090001','3511090002','3511090003',
            '3511100001','3511100002','3511100003',
            '3511110001','3511110002','3511110003',
            '3511111001','3511111002','3511111003',
            '3511120001','3511120002','3511120003',
            '3511130001','3511130002','3511130003',
            '3511140001','3511140002','3511140003',
            '3511141001','3511141002','3511141003',
            '3511150005','3511150006','3511150007',
            '3511151001','3511151002','3511151003',
            '3511152001','3511152002','3511152003',
            '3511160002','3511160003','3511160004',
            '3511170001','3511170002','3511170003',
        ];


        // // Insert user untuk setiap id_village
        // foreach ($villages as $i => $villageId) {
        //     DB::table('users')->insert([
        //         'nik' => str_pad((3511222222222222 + $i), 16, '0', STR_PAD_LEFT),
        //         'username' => str_pad((3511222222222222 + $i), 16, '0', STR_PAD_LEFT),
        //         'tanggalLahir' => "1995-01-01",
        //         'namaIbu' => "User" . $i,
        //         'bbPraHamil' => 50.5,
        //         'tinggiBadan' => 160,
        //         'role' => 1,
        //         'id_villages' => $villageId,
        //         'id_subs' => "5a2419c2-03c3-4dac-9060-132986ab3818",
        //         'password' => Hash::make(3511222222222222 + $i),
        //         'created_at' => $nowDate,
        //     ]);
        // }

        // // SEEDER UNTUK BERAT BADAN IBU HAMIL HISTORY
        // $userCount = 72; // total id_users
        //         $now = Carbon::now();

        //         for ($userId = 1; $userId <= $userCount; $userId++) {
        //             for ($i = 0; $i < 9; $i++) {
        //                 $date = $now->copy()->subMonths($i)->startOfMonth();

        //                 berat_badan::create([
        //                     'bbNow' => rand(8, 20), // nilai berat badan acak antara 8-20 kg
        //                     'id_users' => $userId,
        //                     'created_at' => $date,
        //                     'updated_at' => $date
        //                 ]);
        //             }
        //         }

        // Ambil semua user
        $allUsers = User::all();

        // Hitung jumlah user yang akan diambil (1/8 dari total)
        $jumlahUser = floor($allUsers->count() / 8);

        // Ambil random user sebanyak 1/8
        $selectedUsers = $allUsers->random($jumlahUser);

        $data = [];

        foreach ($selectedUsers as $user) {
            // Berat badan acak (antara 45 - 80 kg misalnya)
            $bbNow = fake()->randomFloat(1, 45, 60);

            $data[] = [
                'bbNow' => $bbNow,
                'id_users' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Masukkan ke tabel
        DB::table('berat_badan')->insert($data);

        //seeding 1000+ user jambesari
        $faker = Faker::create('id_ID');
        $faker->addProvider(new \Faker\Provider\id_ID\Person($faker));

        // ====== Bagian Nama ======
        $namaDepan = [
            'Siti', 'Nur', 'Hj', 'Lilik', 'Sri', 'Rukayah', 'Rohmah', 'Sulastri', 'Suwarti',
            'Mulyani', 'Kusmiati', 'Rohani', 'Muniroh', 'Yuliani', 'Hartini', 'Masrifah',
            'Sumini', 'Sulami', 'Rohimah', 'Minarsih', 'Wahyuni', 'Latifah', 'Masruroh',
            'Rusmini', 'Marfuah', 'Ainun', 'Zainab', 'Kusnul', 'Rohayati', 'Muslihah',
            'Wulan', 'Anik', 'Endah', 'Fatimatus', 'Ummi', 'Supiyatun', 'Astutik', 'Maryani',
            'Tatik', 'Suni', 'Ningsih', 'Sutina', 'Suhai', 'Maryati', 'Muzayanah', 'Sauda',
            'Eli', 'Sumiyati', 'Astuti', 'Hasanah', 'Sundari', 'Yuli', 'Nurul', 'Samsiah',
            'Chunaini', 'Cicinur', 'Sunarsih', 'Patima', 'Sukartini', 'Linda', 'Agustina',
            'Rismiyati', 'Mutik', 'Asiami', 'Husaimah', 'Aliyatin', 'Susyati', 'Astami',
            'Emi', 'Toyyiba', 'Halimatus', 'Waniah', 'Rusmiati', 'Hairiyah', 'Ani',
            'Ribka', 'Titin', 'Nurholifah', 'Indah', 'Budiwarti', 'Indra', 'Itna',
            'Henrita', 'Diah', 'Eva', 'Vindi', 'Elsa', 'Ihwatin', 'Rahmi', 'Wilujeng',
            'Zayyanah', 'Lutviyah', 'Novita', 'Juhriyah', 'Lela', 'Miga', 'Rahmawati',
            'Enur', 'Sitti', 'Lutfiah', 'Artiyah', 'Misnati', 'Astika', 'Sumina',
            'Armiati', 'Yayuk', 'Suwati', 'Suarni', 'Saliha', 'Hatina', 'Sulima',
            'Rumyati', 'Salima', 'Badriyah', 'Asmuni', 'Sumiati', 'Puryati', 'Asiseh',
            'Ida', 'Djuma\'ati', 'Roka\'iyah', 'Niti', 'Sumia', 'Faridah', 'Monati',
            'Legi', 'Ruhannah', 'Hatija', 'Sumrati', 'Rahma', 'Munati', 'Jariyah',
            'Hozaimah', 'Zainap', 'Hosna', 'Partini', 'Sasmiati', 'Minari', 'Rahmaiya',
            'Maryam', 'Mani', 'Jumiya', 'Hariyah', 'Mudin', 'Maimunah', 'Asisah',
            'Asmiani', 'Nurhasanati', 'Mesra', 'Maimuna', 'Juhanah', 'Halila', 'Puji',
            'Hosniatus', 'Sittiawi', 'Komariyah'
        ];

        $namaTengah = [
            'Aini', 'Rahayu', 'Wahyuni', 'Puspita', 'Lestari', 'Rahayuning', 'Masdira', 
            'Swara', 'Asmudar', 'Rahayu', 'Fatimah', 'Khotimah', 'Puspitasari', 'Rohmah', 
            'Maryam', 'Latifah', 'Munawaroh', 'Zahro', 'Sari', 'Kusuma', 'Anggraini', 
            'Hidayah', 'Fauziah', 'Widya', 'Ratna', 'Handayani', 'Susanti', 'Rahmawati'
        ];

        $namaBelakang = [
            'Rahayu', 'Sari', 'Wahyuni', 'Lestari', 'Rahmawati', 'Fatimah', 'Handayani', 
            'Kusuma', 'Zahro', 'Latifah', 'Puspitasari', 'Hidayah', 'Anggraini', 'Masdira',
            'Rahmah', 'Pertiwi', 'Fitriani', 'Munawaroh', 'Khotimah', 'Susanti', 'Rohmah'
        ];

        // ====== Village IDs JAMBESARI butuh total 1120 data keseluruhan======
        $villageJambesari  = [
            '3511031002',
            '3511031003',
            '3511031004',
            '3511031005',
            '3511031006',
            '3511031007',
            '3511031008',
            '3511031010'
        ];

        // id villages butuh 10-30 data setiap desa
        $villageLain  = [
            '3511010001','3511010002','3511010003',
            '3511020001','3511020002','3511020003',
            '3511030001','3511030002','3511030004',
            // Dihapus: 3511031002, 3511031003, 3511031004
            '3511040001','3511040002','3511040003',
            '3511050001','3511050002','3511050003',
            '3511060001','3511060006','3511060007',
            '3511061001','3511061002','3511061003',
            '3511070001','3511070002','3511070003',
            '3511080001','3511080002','3511080004',
            '3511090001','3511090002','3511090003',
            '3511100001','3511100002','3511100003',
            '3511110001','3511110002','3511110003',
            '3511111001','3511111002','3511111003',
            '3511120001','3511120002','3511120003',
            '3511130001','3511130002','3511130003',
            '3511140001','3511140002','3511140003',
            '3511141001','3511141002','3511141003',
            '3511150005','3511150006','3511150007',
            '3511151001','3511151002','3511151003',
            '3511152001','3511152002','3511152003',
            '3511160002','3511160003','3511160004',
            '3511170001','3511170002','3511170003',
        ];

        $users = [];

        // === Buat user Jambesari (1120 user total) ===
        foreach ($villageJambesari as $villageId) {
            $jumlah = rand(130, 150); // tiap desa Jambesari sekitar 130–150 user
            for ($i = 0; $i < $jumlah; $i++) {
                $users[] = $this->buatUser($faker, $villageId, $namaDepan, $namaTengah, $namaBelakang);
            }
        }

        // === Desa lainnya (10–30 user per desa) ===
        foreach ($villageLain as $villageId) {
            $jumlah = rand(10, 30);
            for ($i = 0; $i < $jumlah; $i++) {
                $users[] = $this->buatUser($faker, $villageId, $namaDepan, $namaTengah, $namaBelakang);
            }
        }

        // === Tambahkan 30 user tambahan dengan NIK prefix 3509 ===
        for ($i = 0; $i < 30; $i++) {
            $nik = '3509' . str_pad($faker->numberBetween(100000000000, 999999999999), 12, '0', STR_PAD_LEFT);
            $jumlahKata = $faker->numberBetween(2, 3);
            $namaIbu = $faker->randomElement($namaDepan);
            if ($jumlahKata >= 2) $namaIbu .= ' ' . $faker->randomElement($namaTengah);
            if ($jumlahKata === 3) $namaIbu .= ' ' . $faker->randomElement($namaBelakang);
            $username = Str::slug(strtolower(str_replace(' ', '', $namaIbu)));

            $users[] = [
                'nik' => $nik,
                'username' => $username,
                'namaIbu' => $namaIbu,
                'tanggalLahir' => $faker->dateTimeBetween('-45 years', '-20 years')->format('Y-m-d'),
                'bbPraHamil' => $faker->randomFloat(1, 40, 80),
                'tinggiBadan' => $faker->randomFloat(1, 140, 175),
                'role' => 2,
                // random dari desa selain Jambesari biar lebih menyebar
                'id_villages' => $faker->randomElement($villageLain),
                'password' => Hash::make('password'),
                'id_subs' => null,
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // === Insert ke database ===
        foreach (array_chunk($users, 200) as $chunk) {
            DB::table('users')->insert($chunk);
        }
    }

    private function buatUser($faker, $villageId, $namaDepan, $namaTengah, $namaBelakang)
    {
        $nik = '3511' . str_pad($faker->unique()->numberBetween(100000000000, 999999999999), 12, '0', STR_PAD_LEFT);
        $jumlahKata = $faker->numberBetween(2, 3);
        $namaIbu = $faker->randomElement($namaDepan);
        if ($jumlahKata >= 2) $namaIbu .= ' ' . $faker->randomElement($namaTengah);
        if ($jumlahKata === 3) $namaIbu .= ' ' . $faker->randomElement($namaBelakang);
        $username = Str::slug(strtolower(str_replace(' ', '', $namaIbu)));

        return [
            'nik' => $nik,
            'username' => $username,
            'namaIbu' => $namaIbu,
            'tanggalLahir' => $faker->dateTimeBetween('-45 years', '-20 years')->format('Y-m-d'),
            'bbPraHamil' => $faker->randomFloat(1, 40, 80),
            'tinggiBadan' => $faker->randomFloat(1, 140, 175),
            'role' => 1,
            'id_villages' => $villageId,
            'password' => Hash::make('password'),
            'id_subs' => null,
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}