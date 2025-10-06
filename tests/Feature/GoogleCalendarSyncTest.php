<?php

use App\Models\Category;
use App\Models\Event;
use App\Models\User;
use App\Observers\EventObserver;
use App\Services\Calendar\GoogleCalendarSyncService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery as m;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->calendarSync = m::mock(GoogleCalendarSyncService::class);
    $this->app->instance(GoogleCalendarSyncService::class, $this->calendarSync);

    Event::flushEventListeners();
    Event::observe(EventObserver::class);

    $this->admin = User::factory()->create(['role' => 'admin']);
    $this->category = Category::create([
        'name' => 'Pengujian',
        'slug' => 'pengujian',
    ]);
});

afterEach(function () {
    m::close();
});

function makeEventPayload(array $overrides = []): array
{
    return array_merge([
        'title' => 'Bimbingan Teknis',
        'description' => 'Pelatihan Implementasi Sistem Baru',
        'start_time' => now()->addDay(),
        'end_time' => now()->addDay()->addHours(2),
        'location' => 'Aula Besar',
        'status' => 'Terjadwal',
    ], $overrides);
}

it('syncs to google calendar when an event is created', function () {
    $this->calendarSync->shouldReceive('sync')->once()->andReturnTrue();
    $this->calendarSync->shouldReceive('delete')->never();

    Event::create(array_merge(makeEventPayload(), [
        'creator_id' => $this->admin->id,
        'category_id' => $this->category->id,
    ]));
});

it('resyncs when a significant field changes', function () {
    $this->calendarSync->shouldReceive('sync')->twice()->andReturnTrue();
    $this->calendarSync->shouldReceive('delete')->never();

    $event = Event::create(array_merge(makeEventPayload(), [
        'creator_id' => $this->admin->id,
        'category_id' => $this->category->id,
    ]));

    $event->update(['title' => 'Judul Baru']);
});

it('removes the google calendar event when status is cancelled', function () {
    $this->calendarSync->shouldReceive('sync')->once()->andReturnTrue();
    $this->calendarSync->shouldReceive('delete')->once()->andReturnTrue();

    $event = Event::create(array_merge(makeEventPayload(), [
        'creator_id' => $this->admin->id,
        'category_id' => $this->category->id,
    ]));

    $event->update(['status' => 'Dibatalkan']);
});

it('removes the google calendar event when the event is deleted', function () {
    $this->calendarSync->shouldReceive('sync')->once()->andReturnTrue();
    $this->calendarSync->shouldReceive('delete')->once()->andReturnTrue();

    $event = Event::create(array_merge(makeEventPayload(), [
        'creator_id' => $this->admin->id,
        'category_id' => $this->category->id,
    ]));

    $event->delete();
});

it('allows admin to trigger manual sync via controller action', function () {
    $this->calendarSync->shouldReceive('sync')->twice()->andReturnUsing(function (Event $event) {
        $event->forceFill([
            'google_calendar_sync_status' => 'synced',
            'google_calendar_last_error' => null,
        ])->saveQuietly();

        return true;
    });
    $this->calendarSync->shouldReceive('delete')->never();

    $event = Event::create(array_merge(makeEventPayload(), [
        'creator_id' => $this->admin->id,
        'category_id' => $this->category->id,
    ]));

    $this->actingAs($this->admin);

    $response = $this->post(route('admin.events.calendar.sync', $event), [
        'action' => 'sync',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');
});

it('allows admin to delete google calendar via controller action', function () {
    $this->calendarSync->shouldReceive('sync')->once()->andReturnTrue();
    $this->calendarSync->shouldReceive('delete')->once()->andReturnUsing(function (Event $event) {
        $event->forceFill([
            'google_calendar_event_id' => null,
            'google_calendar_link' => null,
            'google_calendar_sync_status' => 'deleted',
            'google_calendar_last_error' => null,
        ])->saveQuietly();

        return true;
    });

    $event = Event::create(array_merge(makeEventPayload(), [
        'creator_id' => $this->admin->id,
        'category_id' => $this->category->id,
    ]));

    $this->actingAs($this->admin);

    $response = $this->post(route('admin.events.calendar.sync', $event), [
        'action' => 'delete',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('success');
});

it('stores localized error when credentials file is missing', function () {
    Event::flushEventListeners();

    $event = Event::create(array_merge(makeEventPayload(), [
        'creator_id' => $this->admin->id,
        'category_id' => $this->category->id,
    ]));

    $service = new GoogleCalendarSyncService();

    config([
        'google-calendar.sync_enabled' => true,
        'google-calendar.auth_profiles.service_account.credentials_json' => 'storage/app/google-calendar/not-found.json',
        'google-calendar.calendar_id' => 'primary',
    ]);

    $result = $service->sync($event);

    expect($result)->toBeFalse();

    $event->refresh();

    expect($event->google_calendar_sync_status)->toBe('failed');
    expect($event->google_calendar_last_error)
        ->toContain('File kredensial Google Calendar tidak ditemukan');
});

it('normalizes relative credential path before syncing', function () {
    $relativePath = 'storage/app/google-calendar/normalized.json';
    $absolutePath = base_path($relativePath);

    if (! is_dir(dirname($absolutePath))) {
        mkdir(dirname($absolutePath), 0777, true);
    }

    file_put_contents($absolutePath, '{}');

    $service = new GoogleCalendarSyncService();

    config([
        'google-calendar.default_auth_profile' => 'service_account',
        'google-calendar.auth_profiles.service_account.credentials_json' => $relativePath,
    ]);

    $method = new \ReflectionMethod($service, 'ensureCredentialsAvailable');
    $method->setAccessible(true);

    $method->invoke($service);

    expect(config('google-calendar.auth_profiles.service_account.credentials_json'))->toBe($absolutePath);

    unlink($absolutePath);
});

it('includes creator and participants as accepted attendees', function () {
    config([
        'google-calendar.default_event_settings.attendees' => ['default@example.com'],
    ]);

    $participant = User::factory()->create(['email' => 'participant@example.com']);

    $event = Event::withoutEvents(function () use ($participant) {
        $event = Event::create(array_merge(makeEventPayload(), [
            'creator_id' => $this->admin->id,
            'category_id' => $this->category->id,
        ]));

        $event->participants()->attach($participant);

        return $event;
    });

    $service = new GoogleCalendarSyncService();

    $method = new \ReflectionMethod($service, 'buildPayload');
    $method->setAccessible(true);

    $payload = $method->invoke($service, $event->fresh('creator'));

    expect($payload)->toHaveKey('attendees');
    expect($payload['guestsCanSeeOtherGuests'])->toBeTrue();
    expect($payload['guestsCanInviteOthers'])->toBeFalse();
    expect($payload['guestsCanModify'])->toBeFalse();
    expect($payload['anyoneCanAddSelf'])->toBeFalse();

    $attendees = collect($payload['attendees']);

    $emails = $attendees->map(fn ($attendee) => $attendee->getEmail());

    expect($emails)->toContain('default@example.com');
    expect($emails)->toContain($this->admin->email);
    expect($emails)->toContain('participant@example.com');

    $attendees->each(function ($attendee) {
        expect($attendee->getResponseStatus())->toBe('accepted');
    });
});
