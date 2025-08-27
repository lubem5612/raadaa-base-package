<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    'route' => [
        'prefix' => 'api',
        'middleware' => 'api'
    ],

    'storage' => [
        'prefix' => env('STORAGE_PREFIX', ''),
        'driver' => env('STORAGE_DRIVER', 'local'),
    ],

    'termii' => [
        'username'      => env('TERMII_USERNAME', ''),
        'message_type'  => env('TERMII_MESSAGE_TYPE', 'plain'),
        'channel'       => env('TERMII_MESSAGE_CHANNEL', 'dnd'),
        'api_key'       => env('TERMII_API_KEY', ''),
        'base_url'      => env('TERMII_BASE_URL', 'https://api.ng.termii.com/api/sms/send'),
    ],

    'paystack' => [
        'secret_key'    => env('PAYSTACK_SECRET_KEY', ''),
        'public_key'    => env('PAYSTACK_PUBLIC_KEY', ''),
        'callback_url'  => env('PAYSTACK_CALLBACK_URL', ''),
        'base_url'      => env('PAYSTACK_BASE_URL', 'https://api.paystack.co'),
    ],

    'flutterwave' => [
        'secret_key'        => env('FLUTTERWAVE_SECRET_KEY', ''),
        'public_key'        => env('FLUTTERWAVE_PUBLIC_KEY', ''),
        'redirect_url'      => env('FLUTTERWAVE_REDIRECT_URL', 'https://transave.com.ng/dashboard'),
        'base_url'          => env('FLUTTERWAVE_BASE_URL', 'https://api.flutterwave.com/v3'),
        'encryption_key'    => env('FLUTTERWAVE_ENCRYPTION_KEY', ''),
    ],

    'azure' => [
        'storage_url' => 'https://'.env('AZURE_STORAGE_NAME').'.blob.core.windows.net/'.env('AZURE_STORAGE_CONTAINER'),
        'id' => '.windows.net',
    ],

    's3' => [
        'storage_url' => 'https://'.env('AWS_BUCKET').'.s3.'.env('AWS_DEFAULT_REGION').'.amazonaws.com',
        'id' => 'amazonaws.com',
    ],

    'local' => [
        'storage_url' => '',
        'id' => '',
    ],

    'set_routes' => env('USE_RAADAA_ROUTES', false),

    'endpoints' => [
        /*
        | ___________________________________________________________
        | ENDPOINTS FOR RESOURCE CONTROLLER VIA RESOURCE ACTIONS
        | ___________________________________________________________
        |
        | Endpoints registered for the source actions and endpoints
        | Endpoints registered here are variables that follow these patterns
        |
        | GET: {endpoint}?search={optional search term}&start={optional start date}&end={optional end date}
        | for getting a listing of resources
        |
        | GET: {endpoint}/{id} for getting a specified resource
        |
        | POST: {endpoint} for creating a new resource in storage
        |
        | POST, PUT, PATCH: {endpoint}/{id} for updating a specified resource
        |
        | DELETE: {endpoint}/{id} for deleting a specified resource from storage
        |
        */
        'routes' => [
//            'users' => [
//                'model' =>\App\Models\User::class,
//                'table' => 'users',
//                'rules' => [
//                    'store' => [
//                        'name' => 'required|string|max:150',
//                        'email' => 'nullable|string',
//                        'phone' => 'nullable|string',
//                    ],
//                    'update' => [
//                        'name' => 'sometimes|required|string|max:100',
//                        'email' => 'nullable|string',
//                        'phone' => 'nullable|string',
//                    ]
//                ],
//                'order' => [
//                    'column' => 'created_at',
//                    'pattern' => 'ASC',
//                ],
//                'relationships' => [],
//            ],
        ],

        /*
         |
         | Add prefix to the routes to avoid conflicts with other routes. example /api/{prefix}/
         | Defaults to /api/general/..
         |
         */
        'prefix' => 'raadaa'
    ],
];