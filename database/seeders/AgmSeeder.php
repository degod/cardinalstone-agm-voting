<?php

namespace Database\Seeders;

use App\Models\Agm;
use Illuminate\Database\Seeder;

class AgmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Agm::factory(15)->create();
    }
}
