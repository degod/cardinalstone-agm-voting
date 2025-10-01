<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Example predefined company, only create if registration_number does not exist
        if (!Company::where('registration_number', 'RC000001')->exists()) {
            Company::create([
                'name' => 'CardinalStone Registrars',
                'registration_number' => 'RC000001',
            ]);
        }

        // Random extra companies
        Company::factory(3)->create();
    }
}
