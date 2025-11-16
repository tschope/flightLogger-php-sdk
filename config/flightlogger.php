<?php

return [

    /*
    |--------------------------------------------------------------------------
    | FlightLogger API Token
    |--------------------------------------------------------------------------
    |
    | Your FlightLogger API token for authentication. You can get this token
    | from your FlightLogger account settings.
    |
    | If not set here, the package will try to read from FLIGHTLOGGER_API_TOKEN
    | environment variable.
    |
    */

    'api_token' => env('FLIGHTLOGGER_API_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | API Base URL
    |--------------------------------------------------------------------------
    |
    | The base URL for the FlightLogger API. You normally don't need to change
    | this unless FlightLogger changes their API endpoint.
    |
    */

    'base_url' => env('FLIGHTLOGGER_API_URL', 'https://api.flightlogger.net'),

];
