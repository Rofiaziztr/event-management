<?php

return [

    'sync_enabled' => env('GOOGLE_CALENDAR_SYNC_ENABLED', false),

    'default_auth_profile' => env('GOOGLE_CALENDAR_AUTH_PROFILE', 'service_account'),

    'auth_profiles' => [

        /*
         * Authenticate using a service account.
         */
        'service_account' => [
            /*
             * Path to the json file containing the credentials.
             */
            'credentials_json' => env('GOOGLE_CALENDAR_CREDENTIALS_JSON', storage_path('app/google-calendar/service-account-credentials.json')),
        ],

        /*
         * Authenticate with actual google user account.
         */
        'oauth' => [
            /*
             * Path to the json file containing the oauth2 credentials.
             */
            'credentials_json' => env('GOOGLE_CALENDAR_OAUTH_CREDENTIALS_JSON', storage_path('app/google-calendar/oauth-credentials.json')),

            /*
             * Path to the json file containing the oauth2 token.
             */
            'token_json' => env('GOOGLE_CALENDAR_OAUTH_TOKEN_JSON', storage_path('app/google-calendar/oauth-token.json')),
        ],
    ],

    /*
     *  The id of the Google Calendar that will be used by default.
     */
    'calendar_id' => env('GOOGLE_CALENDAR_ID'),

    /*
     *  Optional calendar ids per environment or feature.
     */
    'calendars' => [
        'primary' => env('GOOGLE_CALENDAR_ID'),
    ],

    /*
     *  The email address of the user account to impersonate.
     */
    'user_to_impersonate' => env('GOOGLE_CALENDAR_IMPERSONATE'),

    /*
     * Default behaviour when pushing updates to Google Calendar.
     */
    'send_updates' => env('GOOGLE_CALENDAR_SEND_UPDATES', 'all'),

    'default_event_settings' => [
        'reminders' => [
            'use_default' => env('GOOGLE_CALENDAR_REMINDERS_USE_DEFAULT', false),
            'email_minutes' => env('GOOGLE_CALENDAR_REMINDER_EMAIL_MINUTES'),
            'popup_minutes' => env('GOOGLE_CALENDAR_REMINDER_POPUP_MINUTES'),
        ],
        'conference' => [
            'enabled' => env('GOOGLE_CALENDAR_CREATE_CONFERENCE', false),
            'solution_type' => env('GOOGLE_CALENDAR_CONFERENCE_TYPE', 'hangoutsMeet'),
        ],
        'attendees' => array_values(array_filter(array_map('trim', explode(',', (string) env('GOOGLE_CALENDAR_DEFAULT_ATTENDEES', ''))))),
    ],

    'timezone' => env('GOOGLE_CALENDAR_TIMEZONE', config('app.timezone', 'UTC')),
];
