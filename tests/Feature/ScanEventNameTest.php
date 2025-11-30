<?php

use App\Models\Event;
use App\Models\User;
use App\Models\Category;

it('returns event title for valid event code when authenticated participant calls endpoint', function () {
    $user = User::factory()->create(['role' => 'participant']);
    $category = Category::factory()->create(['name' => 'Umum']);
    $admin = User::factory()->create(['role' => 'admin', 'full_name' => $category->name . ' Admin']);

    $event = Event::factory()->create([
        'title' => 'Event Test Title',
        'start_time' => now()->subMinutes(10),
        'end_time' => now()->addMinutes(10),
        'creator_id' => $admin->id,
        'category_id' => $category->id,
    ]);

    $event->participants()->attach($user->id);

    $this->actingAs($user)->get('/scan');

    $response = $this->actingAs($user)->get('/scan/event-name?code=' . $event->code);
    $response->assertStatus(200)->assertJsonPath('event.title', 'Event Test Title');
});

it('returns a 404 if event code not found', function () {
    $user = User::factory()->create(['role' => 'participant']);
    $this->actingAs($user)->get('/scan');
    $response = $this->actingAs($user)->get('/scan/event-name?code=NO-SUCH-CODE');
    $response->assertStatus(404);
});
