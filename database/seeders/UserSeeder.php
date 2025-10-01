<?php

namespace Database\Seeders;

use App\Enums\Roles;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // System Admin or Not
        if (!User::where('email', 'admin@cardinalstone.test')->exists()) {
            User::create([
                'email' => 'admin@cardinalstone.test',
                'password' => Hash::make('admin123'),
                'name' => 'System Admin',
                'role' => Roles::ADMIN,
                'is_active' => true,
            ]);
        }

        // artisan command to log the system admin credentials
        $this->command->info('=================================');
        $this->command->info('System Admin Created!');
        $this->command->info('=================================');
        $this->command->info('EMAIL:    admin@cardinalstone.test');
        $this->command->info('PASSWORD: admin123');
        $this->command->info('=================================');

        // Sample shareholders
        User::factory(5)->create([
            'role' => Roles::SHAREHOLDER,
        ]);
        $this->command->info('=================================');
        $this->command->info('10 Shareholders Created!');
        $this->command->info('=================================');
        $this->command->info('PASSWORD: password');
        $this->command->info('=================================');
    }
}
