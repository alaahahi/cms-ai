<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HospitalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('hospitals')->insert([
            ['name' => 'PAR Hospital', 'subdomain' => 'https://parhospital.intellijapp.com/'],
            ['name' => 'DPH Hospital', 'subdomain' => 'https://dph.intellijapp.com/'],
            // Add more hospitals as needed
        ]);
    }
}
