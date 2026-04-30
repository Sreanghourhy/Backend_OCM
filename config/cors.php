<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */
    'enabled' => true ,

    'paths' => [
        // 'api/*' 
        // , 'sanctum/csrf-cookie'
        'storage/*',
        'api/admin/*' ,
        'api/authcenter/*' ,
        'api/meeting/*' ,
        'api/client/*'  ,
        'api/attendant/*' ,
        'api/hradmin/*'
    ],

    'allowed_methods' => [
            // '*'
        'GET' ,
        'POST' ,
        'PUT' ,
        'PATCH' , 
        'DELETE' ,
        'OPTIONS'
    ],
    'allowed_origins' => explode( ',' , env('ALLOWED_CORS','http://127.0.0.1:3005,http://127.0.0.1:3000,http://127.0.0.1:3001,http://127.0.0.1:3002,http://127.0.0.1:3009,https://localdoc.ocm.gov.kh' ) )
    // [
    //     // '*' ,
    //     'http://127.0.0.1:3005' , //  Portal UI
    //     'http://127.0.0.1:3000' , // ADMIN UI
    //     'http://127.0.0.1:3001' , // MEETING UI
    // ]
    ,

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];
