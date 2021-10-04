<?php

return [

    /*
    |--------------------------------------------------------------------------
    | SMPP Settings
    |--------------------------------------------------------------------------
    |
    | Usually SMPP providers provide these settings to their clients.
    | Please refer to official SMPP protocol specification v3.4 to learn more about TON and NPI settings.
    |
    */

    'default' => [
        'service_type' => env('SMPP_SERVICE_TYPE'),
        'source_addr_ton' => env('SMPP_SOURCE_ADDR_TON'),
        'source_addr_npi' => env('SMPP_SOURCE_ADDR_NPI'),
        'source_addr' => env('SMPP_SOURCE_ADDR'),
        'dest_addr_ton' => env('SMPP_DEST_ADDR_TON'),
        'dest_addr_npi' => env('SMPP_DEST_ADDR_NPI'),
        'dest_addr' => env('SMPP_DEST_ADDR'),
        'esm_class' => env('SMPP_ESM_CLASS'),
        'protocol_id' => env('SMPP_PROTOCOL_ID'),
        'priority_flag' => env('SMPP_PRIORITY_FLAG'),
        'schedule_delivery_time' => env('SMPP_SCHEDULE_DELIVERY_TIME'),
        'validity_period' => env('SMPP_VALIDITY_PERIOD'),
        'registered_delivery' => env('SMPP_REGISTERED_DELIVERY'),
        'replace_if_present_flag' => env('SMPP_REPLACE_IF_PRESENT_FLAG'),
        'data_coding' => env('SMPP_DATA_CODING'),
        'sm_default_msg_id' => env('SMPP_SM_DEFAULT_MSG_ID'),
        'sm_length' => env('SMPP_SM_LENGTH'),
        'short_message' => env('SMPP_SHORT_MESSAGE'),
    ],

    /*
    |--------------------------------------------------------------------------
    | SMPP Transport Settings
    |--------------------------------------------------------------------------
    |
    | For all SMPP errors listed in "transport.catchables", exceptions
    | thrown by SMPP will be suppressed and just logged.
    |
    */

    'transport' => [
        'catchables' => [
            SMPP::ESME_RBINDFAIL,
            SMPP::ESME_RINVCMDID
        ],
        'force_ipv4' => true,
        'debug' => false
    ],

    /*
    |--------------------------------------------------------------------------
    | SMPP Client Settings
    |--------------------------------------------------------------------------
    */

    'client' => [
        'system_type' => 'default',
        'null_terminate_octetstrings' => false,
        'debug' => false
    ]
];