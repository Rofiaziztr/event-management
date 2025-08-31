<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Document;
use App\Models\Event;
use App\Models\User;

class DocumentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Document::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'event_id' => Event::factory(),
            'uploader_id' => User::factory(),
            'title' => fake()->sentence(4),
            'type' => fake()->randomElement(["Notulensi","Materi","Foto","Video"]),
            'content' => fake()->paragraphs(3, true),
            'file_path' => fake()->regexify('[A-Za-z0-9]{255}'),
            'created_at' => fake()->dateTime(),
        ];
    }
}
