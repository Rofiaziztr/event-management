<?php

use App\Models\Attendance;
use App\Models\Event;
use App\Models\User;
use App\Models\Category;

it('stores latitude and longitude when user scans QR', function () {
    // create participant user
    $user = User::factory()->create(['role' => 'participant']);

    // create category and admin for the event factory
    $category = Category::factory()->create(['name' => 'Umum']);
    $admin = User::factory()->create(['role' => 'admin', 'full_name' => $category->name . ' Admin']);

    // create event active now
    $event = Event::factory()->create([
        'start_time' => now()->subMinutes(10),
        'end_time' => now()->addMinutes(10),
        'creator_id' => $admin->id,
        'category_id' => $category->id,
    ]);

    // attach user as participant
    $event->participants()->attach($user->id);

    // login and get CSRF
    $this->actingAs($user)->get('/scan');
    $token = session('_token');

    $latitude = -6.200000;
    $longitude = 106.816666;

    $response = $this->actingAs($user)->post('/scan', [
        '_token' => $token,
        'event_code' => $event->code,
        'latitude' => $latitude,
        'longitude' => $longitude,
    ]);

    $response->assertRedirect(route('scan.index'));

    $this->assertDatabaseHas('attendances', [
        'event_id' => $event->id,
        'user_id' => $user->id,
        'latitude' => $latitude,
        'longitude' => $longitude,
    ]);
});
