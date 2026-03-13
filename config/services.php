<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    | OAuth 2.0 providers: Discord & Spotify
    |--------------------------------------------------------------------------
    */

    'discord' => [
        'client_id'     => env('DISCORD_CLIENT_ID'),
        'client_secret' => env('DISCORD_CLIENT_SECRET'),
        'redirect'      => env('DISCORD_REDIRECT_URI'),
    ],

    'spotify' => [
        'client_id'     => env('SPOTIFY_CLIENT_ID'),
        'client_secret' => env('SPOTIFY_CLIENT_SECRET'),
        'redirect'      => env('SPOTIFY_REDIRECT_URI'),
    ],

];
