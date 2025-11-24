<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\EventCalendarSync;
use App\Models\Event;
use App\Models\User;

class EventCalendarSyncFactory extends Factory
{
    protected $model = EventCalendarSync::class;

    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'user_id' => User::factory(),
            'google_event_id' => $this->faker->uuid(),
            'synced_at' => now(),
            'last_sync_attempt' => now(),
            'sync_status' => 'synced',
            'sync_error' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
