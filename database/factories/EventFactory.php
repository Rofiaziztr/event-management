<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Event;
use App\Models\User;

class EventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Event::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'creator_id' => User::factory(),
            'title' => fake()->sentence(4),
            'description' => fake()->text(),
            'start_time' => fake()->dateTime(),
            'end_time' => fake()->dateTime(),
            'location' => fake()->regexify('[A-Za-z0-9]{255}'),
            'status' => fake()->randomElement(["Direncanakan","Berlangsung","Selesai","Dibatalkan"]),
            'created_at' => fake()->dateTime(),
            'updated_at' => fake()->dateTime(),
        ];
    }
}
