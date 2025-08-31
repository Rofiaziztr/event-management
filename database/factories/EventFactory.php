<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Ambil satu user Admin secara acak untuk menjadi creator_id
        // Kita pastikan event dibuat oleh Admin, sesuai logika bisnis
        $adminCreator = User::where('role', 'Admin')->inRandomOrder()->first();

        // Logika untuk tanggal agar end_date selalu setelah start_date
        $startDate = $this->faker->dateTimeBetween('+1 week', '+3 months');
        $endDate = $this->faker->dateTimeBetween($startDate, $startDate->format('Y-m-d H:i:s') . ' +8 hours');

        return [
            'creator_id' => $adminCreator->id,
            'title' => 'Rapat ' . $this->faker->bs(), // e.g., "Rapat integrate synergistic schemas"
            'description' => $this->faker->paragraph(3),
            'start_time' => $startDate,
            'end_time' => $endDate,
            'status' => $this->faker->randomElement(['Direncanakan', 'Berlangsung', 'Selesai', 'Dibatalkan']),
            'location' => 'Ruang ' . $this->faker->randomElement(['A', 'B', 'C']) . ' ' . $this->faker->numberBetween(1, 3) . ', ' . $this->faker->address(),
        ];
    }
}
