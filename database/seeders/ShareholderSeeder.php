<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shareholder;

class ShareholderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Shareholder::factory()->count(20)->create();
    }
}
