<?php

use App\Models\Event;
use App\Models\User;
use App\Models\Category;

it('returns event title by code', function () {
    $category = Category::factory()->create(['name' => 'Umum']);
    $admin = User::factory()->create(['role' => 'admin', 'full_name' => $category->name . ' Admin']);
    $event = Event::factory()->create([
        'creator_id' => $admin->id,
        'category_id' => $category->id,
    ]);

    $user = User::factory()->create(['role' => 'participant']);
    $response = $this->actingAs($user)->get('/scan/event-name?code=' . $event->code);
    $response->assertStatus(200)->assertJson(fn (\Illuminate\Testing\Fluent\AssertableJson $json) =>
        $json->where('success', true)
            ->where('event.id', $event->id)
            ->where('event.title', $event->title)
            ->etc()
    );
});

it('returns 404 when event code not found', function () {
    $user = User::factory()->create(['role' => 'participant']);
    $response = $this->actingAs($user)->get('/scan/event-name?code=INVALID-CODE');
    $response->assertStatus(404)->assertJson(['success' => false]);
});
