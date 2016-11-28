<?php

return [

    //
    // Spotify client settings to authorize api calls.
    //
    'client' => [
        'id'        => env('SPOTIFY_CLIENT_ID', ''),
        'secret'    => env('SPOTIFY_CLIENT_SECRET', ''),
    ],

    //
    // Spotify playlist settings.
    //
    'playlist' => [
        'author'    => 'robindrost',
        'id'        => '1qmMthdPjXr8wr6Qe1cjOe',
    ],

];
