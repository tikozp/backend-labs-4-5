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

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
    'keycloak' => [
        'base_url' => env('https://localhost:7080/auth'),
        'realm' => env('lavarel-realm'),
        'client_id' => env('lavarel-cli'),
        'client_secret' => env('LxZdYzgaYQXeWkq7JKrbWJUgA2vsoDRC'),
        'redirect' => env('http://localhost:7080/callback'),
        'public_key' => env('-----BEGIN PUBLIC KEY-----\nMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAz/2cZHEdstrI0xAthbGC/TqQ7Y/Qm5brRfC3ZIMJozvUH4w4fKqZ4O1nV1OR9x1DLR7tr7odaHlCkCVEvdbzQ5EpeVNII3y8fXsGN/RJUIqopyJTDFkd9DG0FgZlEMZZkCxJfb+q4Hqo0ci5BY+vVNSH77hdelNE2AacLdYeIcBjZPp+LcY/afF1AjHzPPwbomsUiJfjb/PDdTRYpxwUzTf1rCzL1PWkD0QRH5PX2IfnmLpsOp5jJQc/tZrturZ90jtVB29HS/46/J9lJOuQ5wLx1X3iZqERjSdCOBqUJh24TeB5JhdUWLENelh9qIvS2Fs88/L+rId8SAaaNNsuiQIDAQAB\n-----END PUBLIC KEY-----'),
        'token_url' => env('KEYCLOAK_BASE_URL') . '/realms/' . env('KEYCLOAK_REALM') . '/protocol/openid-connect/token',
        'logout_url' => env('KEYCLOAK_BASE_URL') . '/realms/' . env('KEYCLOAK_REALM') . '/protocol/openid-connect/logout',
    ],

];
