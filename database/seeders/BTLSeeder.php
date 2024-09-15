<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BTL;

class BTLSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BTL::factory(20)->create();
    }
}
