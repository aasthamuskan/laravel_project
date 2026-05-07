<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Wipe existing users to avoid duplicates on re-seed
        User::truncate();

        $users = [
            [
                'name'     => 'System Administrator',
                'email'    => 'admin@farm.dev',
                'password' => bcrypt('password'),
                'role'     => 'Admin',
            ],
            [
                'name'     => 'Dr. Priya Sharma',
                'email'    => 'expert@farm.dev',
                'password' => bcrypt('password'),
                'role'     => 'Expert',
            ],
            [
                'name'     => 'Ravi Kumar',
                'email'    => 'farmer@farm.dev',
                'password' => bcrypt('password'),
                'role'     => 'Farmer',
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        $this->command->info('[UserSeeder] 3 users created (admin, expert, farmer).');
    }
}
