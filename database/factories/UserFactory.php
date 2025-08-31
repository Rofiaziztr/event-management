<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'nip' => fake()->regexify('[A-Za-z0-9]{50}'),
            'full_name' => fake()->regexify('[A-Za-z0-9]{255}'),
            'position' => fake()->regexify('[A-Za-z0-9]{100}'),
            'work_unit' => fake()->regexify('[A-Za-z0-9]{100}'),
            'email' => fake()->safeEmail(),
            'password' => fake()->password(),
            'role' => fake()->randomElement(["admin","peserta"]),
            'created_at' => fake()->dateTime(),
            'updated_at' => fake()->dateTime(),
        ];
    }
}
