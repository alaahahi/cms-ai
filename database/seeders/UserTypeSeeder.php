<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTypeSeeder extends Seeder
{
    public function run()
    {
        $types = [
            ['name' => 'admin'],
            ['name' => 'data_entry'],
            ['name' => 'seles'],
            ['name' => 'doctor'],
            ['name' => 'account'],
            ['name' => 'hospital'],
            ['name' => 'mobile'],
        ];

        foreach ($types as $type) {
            DB::table('user_type')->updateOrInsert(
                ['name' => $type['name']],
                $type
            );
        }
    }
}
