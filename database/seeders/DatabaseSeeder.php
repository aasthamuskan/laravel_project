<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Crop;
use App\Models\Advisory;

/**
 * DatabaseSeeder
 *
 * Seeds development data for all three roles and creates a comprehensive
 * advisory matrix covering all crops × seasons × weather conditions.
 *
 * IMPORTANT: Passwords are hashed via bcrypt. No plaintext passwords stored.
 *
 * Default credentials (for development only):
 *  admin@farm.dev   / password
 *  expert@farm.dev  / password
 *  farmer@farm.dev  / password
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CropSeeder::class,
            AdvisorySeeder::class,
        ]);
    }
}
