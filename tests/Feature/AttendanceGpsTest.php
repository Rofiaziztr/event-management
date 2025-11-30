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

it('rejects attendance if geolocation is not provided (user denied/block for require_gps event)', function () {
    $user = User::factory()->create(['role' => 'participant']);
    $category = Category::factory()->create(['name' => 'Umum']);
    $admin = User::factory()->create(['role' => 'admin', 'full_name' => $category->name . ' Admin']);

    $event = Event::factory()->create([
        'start_time' => now()->subMinutes(10),
        'end_time' => now()->addMinutes(10),
        'creator_id' => $admin->id,
        'category_id' => $category->id,
        'require_gps' => true,
    ]);

    $event->participants()->attach($user->id);

    $this->actingAs($user)->get('/scan');
    $token = session('_token');

    $response = $this->actingAs($user)->post('/scan', [
        '_token' => $token,
        'event_code' => $event->code,
        // No latitude/longitude provided, simulating blocked geolocation
    ]);

    // If the user doesn't provide coordinates (no form fields set), we show a friendly error message
    $response->assertSessionHas('error');
    $this->assertDatabaseMissing('attendances', [
        'event_id' => $event->id,
        'user_id' => $user->id,
    ]);
});

it('rejects attendance without GPS even if event does not require GPS (global enforcement)', function () {
    $user = User::factory()->create(['role' => 'participant']);
    $category = Category::factory()->create(['name' => 'Umum']);
    $admin = User::factory()->create(['role' => 'admin', 'full_name' => $category->name . ' Admin']);

    $event = Event::factory()->create([
        'start_time' => now()->subMinutes(10),
        'end_time' => now()->addMinutes(10),
        'creator_id' => $admin->id,
        'category_id' => $category->id,
        'require_gps' => false,
    ]);

    $event->participants()->attach($user->id);

    $this->actingAs($user)->get('/scan');
    $token = session('_token');

    $response = $this->actingAs($user)->post('/scan', [
        '_token' => $token,
        'event_code' => $event->code,
        // No latitude/longitude provided, allowed for non-require_gps events
    ]);

    // With global enforcement, missing GPS should cause a flash error and prevent attendance
    $response->assertSessionHas('error');
    $this->assertDatabaseMissing('attendances', [
        'event_id' => $event->id,
        'user_id' => $user->id,
    ]);
});

it('validates latitude and longitude are in range and rejects invalid coordinates', function () {
    $user = User::factory()->create(['role' => 'participant']);
    $category = Category::factory()->create(['name' => 'Umum']);
    $admin = User::factory()->create(['role' => 'admin', 'full_name' => $category->name . ' Admin']);

    $event = Event::factory()->create([
        'start_time' => now()->subMinutes(10),
        'end_time' => now()->addMinutes(10),
        'creator_id' => $admin->id,
        'category_id' => $category->id,
    ]);

    $event->participants()->attach($user->id);

    $this->actingAs($user)->get('/scan');
    $token = session('_token');

    // Invalid latitude
    $response = $this->actingAs($user)->post('/scan', [
        '_token' => $token,
        'event_code' => $event->code,
        'latitude' => 999,
        'longitude' => 999,
    ]);

    $response->assertSessionHasErrors(['latitude', 'longitude']);
    $this->assertDatabaseMissing('attendances', [
        'event_id' => $event->id,
        'user_id' => $user->id,
    ]);
});

it('rejects attendance when latitude and longitude are empty strings', function () {
    $user = User::factory()->create(['role' => 'participant']);
    $category = Category::factory()->create(['name' => 'Umum']);
    $admin = User::factory()->create(['role' => 'admin', 'full_name' => $category->name . ' Admin']);

    $event = Event::factory()->create([
        'start_time' => now()->subMinutes(10),
        'end_time' => now()->addMinutes(10),
        'creator_id' => $admin->id,
        'category_id' => $category->id,
    ]);

    $event->participants()->attach($user->id);

    $this->actingAs($user)->get('/scan');
    $token = session('_token');

    $response = $this->actingAs($user)->post('/scan', [
        '_token' => $token,
        'event_code' => $event->code,
        'latitude' => '',
        'longitude' => '',
    ]);

    // For empty strings, treat as missing coordinates and show the friendly error message
    $response->assertSessionHas('error');
    $this->assertDatabaseMissing('attendances', [
        'event_id' => $event->id,
        'user_id' => $user->id,
    ]);
});

it('rejects attendance when latitude and longitude are the string "null"', function () {
    $user = User::factory()->create(['role' => 'participant']);
    $category = Category::factory()->create(['name' => 'Umum']);
    $admin = User::factory()->create(['role' => 'admin', 'full_name' => $category->name . ' Admin']);

    $event = Event::factory()->create([
        'start_time' => now()->subMinutes(10),
        'end_time' => now()->addMinutes(10),
        'creator_id' => $admin->id,
        'category_id' => $category->id,
    ]);

    $event->participants()->attach($user->id);

    $this->actingAs($user)->get('/scan');
    $token = session('_token');

    $response = $this->actingAs($user)->post('/scan', [
        '_token' => $token,
        'event_code' => $event->code,
        'latitude' => 'null',
        'longitude' => 'null',
    ]);

    // If string "null" is passed for numeric latitude/longitude, validation will return numeric errors
    $response->assertSessionHasErrors(['latitude', 'longitude']);
    $this->assertDatabaseMissing('attendances', [
        'event_id' => $event->id,
        'user_id' => $user->id,
    ]);
});
