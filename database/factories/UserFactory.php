<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserFactory extends Factory
{
    protected $model = User::class;
    protected static ?string $password;

    public function definition(): array
    {
        $faker = \Faker\Factory::create('id_ID');

        // === LOGIKA NIP BARU YANG REALISTIS ===

        // 1. Tanggal Lahir (antara 25 - 50 tahun yang lalu)
        $birthDate = Carbon::instance($faker->dateTimeBetween('-50 years', '-25 years'));

        // 2. TMT CPNS (antara 1 - 10 tahun yang lalu, dan harus setelah umur 18 tahun)
        $minHireDate = $birthDate->copy()->addYears(18);
        $maxHireDate = now()->subYear();
        $hireDate = Carbon::instance($faker->dateTimeBetween($minHireDate, $maxHireDate));

        // 3. Jenis Kelamin (1 untuk Pria, 2 untuk Wanita)
        $gender = $faker->randomElement([1, 2]);

        // 4. Nomor Urut
        $sequence = $faker->numerify('###');

        // Gabungkan semua menjadi NIP 18 digit
        $nip = $birthDate->format('Ymd') . $hireDate->format('Ym') . $gender . $sequence;

        // === LOGIKA DIVISI & JABATAN (tetap sama) ===
        $structure = [
            'Mineral' => [
                'Eksplorasi' => ['Staf Geologi', 'Ahli Geokimia', 'Geophysicist', 'Manajer Eksplorasi'],
                'Penambangan' => ['Operator Alat Berat', 'Insinyur Tambang', 'Supervisor Produksi', 'Kepala Teknik Tambang'],
                'Pengolahan' => ['Metalurgist', 'Analis Laboratorium', 'Staf Pengolahan', 'Manajer Pabrik'],
            ],
            'Batu Bara' => [
                'Perencanaan Tambang' => ['Insinyur Perencana', 'Surveyor Tambang', 'Geotechnical Engineer'],
                'Operasional K3' => ['Safety Officer', 'Koordinator K3L', 'Paramedis Tambang'],
                'Logistik & Pengapalan' => ['Staf Logistik', 'Supervisor Pelabuhan', 'Analis Rantai Pasok'],
            ],
            'Panas Bumi' => [
                'Studi Kelayakan' => ['Ahli Geosains', 'Reservoir Engineer', 'Analis Ekonomi Energi'],
                'Pengeboran' => ['Drilling Engineer', 'Mud Logger', 'Supervisor Pengeboran'],
                'Operasi Pembangkit' => ['Operator Pembangkit Listrik', 'Teknisi Mekanikal', 'Insinyur Listrik'],
            ],
            'Sarana Teknik' => [
                'Perawatan Alat Berat' => ['Mekanik Senior', 'Teknisi Hidrolik', 'Planner Perawatan'],
                'Infrastruktur' => ['Insinyur Sipil', 'Pengawas Konstruksi', 'Drafter'],
                'Kelistrikan' => ['Teknisi Listrik Tegangan Tinggi', 'Automation Engineer'],
            ],
            'Umum' => [
                'Keuangan & Akuntansi' => ['Staf Akuntansi', 'Analis Keuangan', 'Manajer Anggaran'],
                'Sumber Daya Manusia' => ['Staf HRD', 'Spesialis Rekrutmen', 'Training Coordinator'],
                'IT & Sistem Informasi' => ['IT Support', 'Programmer', 'Database Administrator'],
            ]
        ];

        $specialty = $this->faker->randomElement(array_keys($structure));
        $division = $this->faker->randomElement(array_keys($structure[$specialty]));
        $position = $this->faker->randomElement($structure[$specialty][$division]);
        $fullName = $faker->name();

        return [
            'nip' => $nip, // Menggunakan NIP baru
            'full_name' => $fullName,
            'specialty' => $specialty,
            'position' => $position,
            'division' => $division,
            'institution' => 'PT ' . $faker->company(), // Ini akan di-override di seeder
            'email' => Str::slug($fullName, '.') . '@' . $faker->freeEmailDomain(),
            'phone_number' => $faker->unique()->phoneNumber(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'role' => 'participant',
            'created_at' => $faker->dateTimeBetween('-2 years', 'now'),
            'updated_at' => now(),
        ];
    }
}