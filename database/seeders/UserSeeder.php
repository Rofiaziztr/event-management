<?php

namespace Database\Seeders;

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
        $admins = [
            ['name' => 'Admin Mineral', 'division' => 'Mineral'],
            ['name' => 'Admin Batu Bara', 'division' => 'Batu Bara'],
            ['name' => 'Admin Panas Bumi', 'division' => 'Panas Bumi'],
            ['name' => 'Admin Sarana Teknik', 'division' => 'Sarana Teknik'],
            ['name' => 'Admin Umum', 'division' => 'Umum'],
        ];

        foreach ($admins as $admin) {
            User::create([
                'full_name' => $admin['name'],
                'email' => strtolower(str_replace(' ', '', $admin['name'])) . '@gmail.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'position' => 'System Administrator',
                'division' => $admin['division'],
                'email_verified_at' => now(), // Langsung set sebagai terverifikasi
            ]);
        }
    }
}