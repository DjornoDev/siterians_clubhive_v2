<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Main Club Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration contains settings for the main school club (SSLG).
    | The main club ID should not be changed unless absolutely necessary.
    |
    */

    'main_club_id' => env('MAIN_CLUB_ID', 1),

    /*
    |--------------------------------------------------------------------------
    | Club Protection Settings
    |--------------------------------------------------------------------------
    |
    | These settings control how the system protects critical clubs
    | and their data.
    |
    */

    'protected_clubs' => [
        env('MAIN_CLUB_ID', 1), // SSLG Club - cannot be deleted
    ],

    /*
    |--------------------------------------------------------------------------
    | Club Hunting Day Settings
    |--------------------------------------------------------------------------
    |
    | Settings related to club hunting day functionality.
    |
    */

    'hunting_day_control' => [
        'main_club_only' => true, // Only main club can control hunting day
    ],
];
