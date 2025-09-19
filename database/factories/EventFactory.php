<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Event;
use App\Models\User;
use App\Models\Category;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        $faker = \Faker\Factory::create('id_ID');

        // Ambil kategori dan admin yang relevan
        $category = Category::inRandomOrder()->first();
        $adminCreator = User::where('role', 'admin')->where('full_name', 'like', '%' . $category->name . '%')->first();

        // Rentang waktu event (6 bulan ke belakang dan 6 bulan ke depan)
        $startTime = $faker->dateTimeBetween('-6 months', '+6 months');
        $endTime = (clone $startTime)->modify('+' . $faker->numberBetween(2, 8) . ' hours');

        // Tentukan status berdasarkan waktu
        $status = 'Terjadwal';
        if ($startTime < now() && $endTime > now()) {
            $status = 'Berlangsung';
        } elseif ($endTime < now()) {
            $status = 'Selesai';
        }
        
        $eventTitles = [
            'Mineral' => ['Rapat Tinjauan RKAB', 'Sosialisasi Teknik Eksplorasi Terbaru', 'Pelatihan Keselamatan Tambang Mineral'],
            'Batu Bara' => ['Evaluasi Produksi Batu Bara Kuartal 3', 'Workshop Pemanfaatan Fly Ash', 'Training Coal Blending'],
            'Panas Bumi' => ['Diskusi Pengembangan Lapangan Geotermal', 'Seminar Pengeboran Sumur Panas Bumi', 'Rapat Koordinasi Operasional Pembangkit'],
            'Sarana Teknik' => ['Pelatihan Perawatan Alat Berat', 'Presentasi Teknologi Infrastruktur Tambang', 'Evaluasi Kinerja Vendor'],
            'Umum' => ['Sosialisasi Kebijakan SDM', 'Rapat Anggaran Tahunan', 'Pelatihan Sistem Informasi Manajemen'],
        ];

        return [
            'code' => 'EVT-' . $this->faker->unique()->numberBetween(1000, 9999),
            'creator_id' => $adminCreator->id,
            'category_id' => $category->id,
            'title' => $this->faker->randomElement($eventTitles[$category->name]),
            'description' => $faker->paragraphs(3, true),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'location' => $faker->randomElement(['Auditorium PSDMBP, Bandung', 'Ruang Rapat Utama, Jakarta', 'Hotel Hilton, Bandung', 'Zoom Meeting Online']),
            'status' => $status,
        ];
    }
}