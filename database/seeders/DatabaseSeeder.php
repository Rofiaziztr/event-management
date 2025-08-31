<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat 1 Admin spesifik untuk login
        User::factory()->create([
            'full_name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        User::factory()->create([
            'full_name' => 'Peserta A',
            'email' => 'peserta@example.com',
            'role' => 'peserta',
        ]);

        // Buat 49 user sebagai Peserta
        User::factory(49)->create([
            'role' => 'peserta',
        ]);

        $this->call([
            EventSeeder::class,
        ]);
    }
}
