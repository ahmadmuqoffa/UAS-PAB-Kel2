<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('members')->insert([
            [
                'name' => 'John Doe',
                'membership_type' => 'VIP',
                'valid_until' => '2024-12-31',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jane Smith',
                'membership_type' => 'Basic',
                'valid_until' => '2024-12-20',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
