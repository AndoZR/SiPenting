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
        for($i = 0; $i <= 10; $i++) {
            DB::table('users')->insert([
                [
                    'username' => 'user'.$i,
                    'password' => hash::make('123123123'),
                    'email' => 'andozroyan850@gmail.com' . $i,
                    'created_at' => $nowDate,
                ],
            ]);
        }
    }
}