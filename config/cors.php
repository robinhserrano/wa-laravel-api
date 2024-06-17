<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Allowed Origins
    |--------------------------------------------------------------------------
    |
    | List the origins that are allowed access to your resources.
    | You can use wildcards (*) to allow all origins if needed.
    | However, it's safer to restrict access to specific origins.
    |
    */

    'allowed_origins' => [
        '*', 
        // Replace with your Flutter Web app origin (e.g., http://localhost:your_flutter_port)
    ],

    /*
    |--------------------------------------------------------------------------
    | Allowed Methods
    |--------------------------------------------------------------------------
    |
    | List the methods that are allowed to access your resources.
    |
    */

    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],

    /*
    |--------------------------------------------------------------------------
    | Allowed Headers
    |--------------------------------------------------------------------------
    |
    | List the headers that are allowed to be sent with the request.
    |
    */

    'allowed_headers' => ['Content-Type', 'X-Auth-Token', 'Authorization'],

    /*
    |--------------------------------------------------------------------------
    | Exposed Headers
    |--------------------------------------------------------------------------
    |
    | List the headers that are exposed to the frontend client.
    |
    */

    'exposed_headers' => ['Authorization'],

    /*
    |--------------------------------------------------------------------------
    | Supports Credentials
    |--------------------------------------------------------------------------
    |
    | If true, allows CORS requests to include cookies.
    |
    */

    'supports_credentials' => true,

    /*
    |--------------------------------------------------------------------------
    | Max Age
    |--------------------------------------------------------------------------
    |
    | Indicates how long (in seconds) the results of a preflight request can be cached.
    |
    */

    'max_age' => 3600,

];

