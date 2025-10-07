# Google Calendar Integration

## Overview

The event management system includes robust Google Calendar integration that automatically syncs events to all invited participants' calendars. The system handles event creation, updates, and deletions while preventing duplicate events and cleaning up orphaned calendar entries.

## Features

-   **Automatic Sync**: Events are automatically synced to all participants' Google Calendars
-   **Duplicate Prevention**: Updates modify existing calendar events instead of creating duplicates
-   **Orphaned Event Cleanup**: Removes calendar events that are no longer in the system
-   **Per-User OAuth**: Each user authorizes their own Google Calendar access
-   **Role-Based Access**: Admin and participant dashboards with appropriate calendar controls

## Setup

### 1. Google Cloud Console Setup

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select existing one
3. Enable the Google Calendar API
4. Create OAuth 2.0 credentials (Web application type)
5. Add authorized redirect URIs:
    - `http://localhost:8000/google-calendar/callback` (development)
    - `https://yourdomain.com/google-calendar/callback` (production)
6. Copy the Client ID and Client Secret

### 2. Environment Configuration

Add to your `.env` file:

```env
GOOGLE_CLIENT_ID=your_client_id_here
GOOGLE_CLIENT_SECRET=your_client_secret_here
GOOGLE_CALENDAR_REDIRECT_URI=http://localhost:8000/google-calendar/callback
```

### 3. Database Migration

Run the migration to create the `event_calendar_sync` table:

```bash
php artisan migrate
```

## Usage

### For Participants

1. Go to the participant dashboard
2. Click "Connect Google Calendar" button
3. Authorize the application to access your calendar
4. Events you participate in will automatically appear in your calendar

### For Administrators

Administrators can manage calendar sync for all users and perform maintenance operations.

## Commands

### Bulk Cleanup Orphaned Events

Clean up orphaned calendar events across all users:

```bash
php artisan app:cleanup-orphaned-calendar-events
```

Clean up orphaned events for a specific user:

```bash
php artisan app:cleanup-orphaned-calendar-events --user_id=123
```

## Architecture

### Core Components

-   **GoogleCalendarAuthController**: Handles OAuth authorization flow
-   **GoogleCalendarService**: Core service for calendar operations
-   **EventObserver**: Automatically syncs events on create/update/delete
-   **EventCalendarSync Model**: Tracks sync status between local events and Google Calendar

### Event Sync Flow

1. **Event Created**: EventObserver triggers sync to all participants
2. **Event Updated**: Existing calendar events are updated (not duplicated)
3. **Event Deleted**: Calendar events are removed from all participants' calendars
4. **Participant Added**: New participant gets the event added to their calendar
5. **Participant Removed**: Event is removed from that participant's calendar

### Error Handling

-   Token refresh on expiry
-   Graceful handling of API rate limits
-   Logging of all sync operations
-   Automatic retry for transient failures

## Troubleshooting

### Common Issues

1. **Events not syncing**: Check if user has authorized Google Calendar access
2. **Duplicate events**: Run the cleanup command to remove orphaned events
3. **Token expired**: Users need to re-authorize if tokens become invalid
4. **API quota exceeded**: Google Calendar API has daily limits (1 billion requests/day, but per-user limits apply)

### Logs

All calendar operations are logged. Check `storage/logs/laravel.log` for detailed information:

```bash
tail -f storage/logs/laravel.log | grep -i calendar
```

### Manual Cleanup

If you need to manually clean up a user's calendar:

1. Find the user ID
2. Run: `php artisan app:cleanup-orphaned-calendar-events --user_id={USER_ID}`
3. Check logs for the cleanup results

## Security Considerations

-   OAuth tokens are encrypted in the database
-   Each user only accesses their own calendar
-   No sensitive data is stored in calendar events
-   All API calls are logged for audit purposes

## API Reference

### GoogleCalendarService Methods

-   `syncEventToUserCalendar(Event $event, User $user)`: Sync single event to user
-   `syncEventToAllParticipants(Event $event)`: Sync event to all participants
-   `removeEventFromUserCalendar(Event $event, User $user)`: Remove event from user's calendar
-   `removeEventFromAllCalendars(Event $event)`: Remove event from all participants' calendars
-   `cleanupOrphanedEvents(User $user)`: Clean orphaned events for user

### EventCalendarSync Model

Tracks the relationship between local events and Google Calendar events:

-   `event_id`: Local event ID
-   `user_id`: User ID
-   `google_event_id`: Google Calendar event ID
-   `synced_at`: Last sync timestamp
-   `status`: Sync status (success/failed)

## Development

### Testing Calendar Integration

Create test events and verify they appear in Google Calendar:

```php
// In tinker
$user = User::find(1);
$event = Event::find(1);
$calendarService = app(GoogleCalendarService::class);
$calendarService->syncEventToUserCalendar($event, $user);
```

### Adding New Calendar Features

1. Extend `GoogleCalendarService` with new methods
2. Update `EventObserver` if needed
3. Add appropriate routes and controllers
4. Update documentation

## Production Deployment

### Environment Variables

Ensure these are set in production:

```env
GOOGLE_CLIENT_ID=production_client_id
GOOGLE_CLIENT_SECRET=production_client_secret
GOOGLE_CALENDAR_REDIRECT_URI=https://yourdomain.com/google-calendar/callback
```

### Scheduled Cleanup

Add to `app/Console/Kernel.php` for periodic cleanup:

```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('app:cleanup-orphaned-calendar-events')
             ->weekly()
             ->sundays()
             ->at('02:00');
}
```

### Monitoring

Monitor calendar sync health:

-   Check application logs for sync failures
-   Monitor Google Cloud Console for API usage
-   Set up alerts for high failure rates

## Support

For issues with Google Calendar integration:

1. Check user authorization status
2. Verify OAuth credentials
3. Review application logs
4. Test with a fresh OAuth authorization
5. Run cleanup commands if duplicates appear
