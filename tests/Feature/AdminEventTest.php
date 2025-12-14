<?php

use App\Models\Event;
use App\Models\User;
use App\Models\Category;

it('allows admin to create, update, and delete events', function () {
    $category = Category::factory()->create(['name' => 'Umum']);
    $admin = User::factory()->create(['role' => 'admin', 'full_name' => $category->name . ' Admin']);

    // Create event via admin route
    $this->actingAs($admin)->get('/admin/events/create');
    $token = session('_token');

    $title = 'Uji Event Baru';

    $response = $this->actingAs($admin)->post('/admin/events', [
        '_token' => $token,
        'title' => $title,
        'category_id' => $category->id,
        'description' => 'Deskripsi event',
        'start_time' => now()->addHour(),
        'end_time' => now()->addHours(2),
        'location' => 'Lokasi Test',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('events', ['title' => $title]);

    $event = Event::where('title', $title)->first();
    expect($event)->not->toBeNull();

    // Update
    $newTitle = 'Uji Event Diperbarui';
    $response = $this->actingAs($admin)->patch('/admin/events/' . $event->id, [
        '_token' => session('_token'),
        'title' => $newTitle,
        'category_id' => $category->id,
        'description' => $event->description,
        'start_time' => $event->start_time->toDateTimeString(),
        'end_time' => $event->end_time->toDateTimeString(),
        'location' => $event->location,
        'status' => 'Terjadwal',
    ]);
    $response->assertRedirect();
    $this->assertDatabaseHas('events', ['title' => $newTitle]);

    // Delete
    $response = $this->actingAs($admin)->delete('/admin/events/' . $event->id, ['_token' => session('_token')]);
    $response->assertRedirect();
    $this->assertDatabaseMissing('events', ['id' => $event->id]);
});
