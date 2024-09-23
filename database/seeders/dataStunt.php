<?php

namespace Database\Seeders;

use League\Csv\Reader;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class dataStunt extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $csvFile = Storage::path('public\stunt.csv');
        $csvFile = storage_path('app/public/stunt.csv');
        $file = fopen($csvFile, "r");

        while (($data = fgetcsv($file, 1000, ",")) !== FALSE) {
            DB::table('data_stunt')->insert([
                'Umur (bulan)' => $data['1'],
                'Panjang Badan (cm) -3 SD' => $data['2'],
                'Panjang Badan (cm) -2 SD' => $data['3'],
                'Panjang Badan (cm) -1 SD' => $data['4'],
                'Panjang Badan (cm) Median' => $data['5'],
                'Panjang Badan (cm) +1 SD' => $data['6'],
                'Panjang Badan (cm) +2 SD' => $data['7'],
                'Panjang Badan (cm) +3 SD' => $data['8'],
                'kelamin' => $data['9'],
            ]);
        }

        fclose($file);
    }
}
