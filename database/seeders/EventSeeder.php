<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Event;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat 50 pengguna dummy
        $users = User::factory(50)->create();

        // Buat 15 event dummy, dan tetapkan creator_id secara acak dari pengguna yang sudah dibuat
        Event::factory(15)->make()->each(function ($event) use ($users) {
            $event->creator_id = $users->random()->id;
            $event->save();
        });
    }
}
