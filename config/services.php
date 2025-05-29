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

    'razorpay' => [
        'key' => env('RAZORPAY_KEY_ID'),
        'secret' => env('RAZORPAY_KEY_SECRET'),
    ],

    'delhivery' => [
        'api_key' => env('DELHIVERY_API_KEY'),
        'client_name' => env('DELHIVERY_CLIENT_NAME'),
        'pickup_location' => env('DELHIVERY_PICKUP_LOCATION'),
        'env' => env('DELHIVERY_ENV', 'test'),
        'base_url' => env('DELHIVERY_ENV', 'test') === 'test' 
            ? 'https://staging-express.delhivery.com' 
            : 'https://track.delhivery.com',
        'return_pin' => env('DELHIVERY_RETURN_PIN'),
        'return_city' => env('DELHIVERY_RETURN_CITY'),
        'return_phone' => env('DELHIVERY_RETURN_PHONE'),
        'return_address' => env('DELHIVERY_RETURN_ADDRESS'),
        'return_state' => env('DELHIVERY_RETURN_STATE'),
        'seller_name' => env('DELHIVERY_SELLER_NAME'),
        'seller_address' => env('DELHIVERY_RETURN_ADDRESS'),
        'gst_number' => env('DELHIVERY_GST_NUMBER'),
        'auto_create_shipment' => env('DELHIVERY_AUTO_CREATE_SHIPMENT', false),
        'use_mock' => env('DELHIVERY_USE_MOCK', false),
    ],

];
