<?php

namespace Database\Seeders;

use App\Models\Vote;
use Illuminate\Database\Seeder;

class VoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Vote::factory()->count(30)->create();
    }
}
