<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Supabase Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Supabase services including Storage API.
    | Get these values from your Supabase Dashboard → Settings → API.
    |
    */

    'url' => env('SUPABASE_URL', ''),

    'key' => env('SUPABASE_KEY', ''),

    'storage' => [
        'bucket' => env('SUPABASE_STORAGE_BUCKET', 'documents'),
    ],

];
