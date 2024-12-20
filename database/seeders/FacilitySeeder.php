<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('facilities')->insert([
            ['name' => 'Gym Room', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Swimming Pool', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
