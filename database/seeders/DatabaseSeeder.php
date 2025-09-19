<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            UserSeeder::class, // Ini akan membuat 5 admin
            EventSeeder::class,  // Ini akan membuat 105 peserta, 40 event, undangan, & kehadiran
        ]);
    }
}