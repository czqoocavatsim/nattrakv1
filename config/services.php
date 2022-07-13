<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'vatsim' => [
        'auth' => [
            'redirect_uri' => env('VATSIM_REDIRECT_URI'),
            'client_id' => env('VATSIM_CLIENT_ID'),
            'secret' => env('VATSIM_SECRET'),
            'endpoint' => env('VATSIM_ENDPOINT')
        ]
    ],

    'tracks' => [
        'endpoint' => env('VNAAATS_TRACKS_ENDPOINT', 'https://tracks.ganderoceanic.ca/'),
        'auto_update' => env('UPDATE_TRACKS', true),
        'override_tmi' => env('OVERRIDE_TMI', null)
    ],

    'pruning' => [
        'prune_msgs' => env('PRUNE_MSGS', true)
    ],

    'clx-filtering' => [
        'update' => [
            'poll_for_updates' => env('POLL_CLX_TABLE', true),
            'auto_populate_table' => env('AUTO_POPULATE_CLX_TABLE', true)
        ]
    ]
];
