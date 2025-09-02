<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    protected static ?string $password;

    public function definition(): array
    {
        // Faker lokal Indonesia
        $faker = \Faker\Factory::create('id_ID');

        return [
            'nip'        => $faker->numerify(str_repeat('#', 18)), // mirip NIP/NIK
            'full_name'  => $faker->name(), // Nama orang Indonesia
            'position'   => $faker->randomElement([
                'Staff',
                'Kepala Bagian',
                'Manager',
                'Supervisor',
                'Direktur',
                'Wakil Direktur',
                'Koordinator',
                'Sekretaris'
            ]),
            'division'   => $faker->randomElement([
                'Keuangan',
                'Sumber Daya Manusia',
                'Teknologi Informasi',
                'Pemasaran',
                'Operasional',
                'Akuntan',
                'Penelitian dan Pengembangan'
            ]),
            'email'      => $faker->unique()->safeEmail(),
            'password'   => static::$password ??= Hash::make('password'),
            'role'       => $faker->randomElement(['admin', 'participant']),
            'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => now(),
        ];
    }
}
