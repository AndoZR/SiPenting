<?php

namespace Database\Seeders;


use App\Models\bayi;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class bayiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Ambil semua user yang memiliki role = 2 (user ibu)
        $ibuUsers = User::where('role', 1)->get();
        $bayiData = [];

        foreach ($ibuUsers as $ibu) {
            // Probabilitas lebih besar untuk 1-2 anak
            $random = rand(1, 100);
            if ($random <= 60) {
                $jumlahAnak = rand(1, 2); // 60% kemungkinan
            } elseif ($random <= 90) {
                $jumlahAnak = 3; // 30% kemungkinan
            } else {
                $jumlahAnak = 4; // 10% kemungkinan
            }

            for ($i = 0; $i < $jumlahAnak; $i++) {
                $jenisKelamin = $faker->randomElement(['Laki-laki', 'Perempuan']);
                $namaAnak = $faker->firstName($jenisKelamin === 'Laki-laki' ? 'male' : 'female') . ' ' . $faker->lastName;

                $bayiData[] = [
                    'nama' => $namaAnak,
                    'tanggalLahir' => $faker->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
                    'kelamin' => $jenisKelamin,
                    'id_users' => $ibu->id,
                ];
            }
        }

        // Insert bertahap agar efisien
        foreach (array_chunk($bayiData, 200) as $chunk) {
            bayi::insert($chunk);
        }
    }
}
