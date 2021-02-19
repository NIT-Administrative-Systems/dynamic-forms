<?php

return [
    'directorySearch' => [
        'baseUrl' => env('DIRECTORY_SEARCH_URL', 'https://northwestern-prod.apigee.net/directory-search'),
        'apiKey' => env('DIRECTORY_SEARCH_API_KEY'),
    ],

    'sso' => [
        // Valid options are: classic, apigee, forgerock-direct
        // The forgerock-direct is for advance use-cases and contingencies.
        'strategy' => env('WEBSSO_STRATEGY', 'apigee'),
        'openAmBaseUrl' => env('WEBSSO_URL_BASE', 'https://uat-nusso.it.northwestern.edu'),
        
        // forgerock-direct & apigee
        'realm' => env('WEBSSO_REALM', 'northwestern'),
        'authTree' => env('WEBSSO_TREE', env('DUO_ENABLED', false) == true ? 'ldap-and-duo' : 'ldap-registry'),
        'cookieName' => env('WEBSSO_COOKIE_NAME', 'nusso'),

        // apigee
        'apigeeBaseUrl' => env('WEBSSO_API_URL_BASE', 'https://northwestern-prod.apigee.net/agentless-websso'),
        'apigeeApiKey' => env('WEBSSO_API_KEY'),
    ],

    'eventHub' => [
        'baseUrl' => env('EVENT_HUB_BASE_URL'),
        'apiKey' => env('EVENT_HUB_API_KEY'),
        'hmacVerificationSharedSecret' => env('EVENT_HUB_HMAC_VERIFICATION_SHARED_SECRET'),
        'hmacVerificationHeader' => env('EVENT_HUB_HMAC_VERIFICATION_HEADER', 'X-HMAC-Signature'),

        // HMAC algorithm we'll register the webhook with -- this must correspond to a type in the EventHub API docs
        'hmacVerificationAlgorithmForRegistration' => env('EVENT_HUB_HMAC_VERIFICATION_ALGORITHM_TYPE_REGISTRATION', 'HmacSHA256'),

         // Matching PHP algorithm type, passed to `hash_hmac()`. You can run `hash_algos()` to see what you have available.
        'hmacVerificationAlgorithmForPHPHashHmac' => env('EVENT_HUB_HMAC_VERIFICATION_ALGORITHM_TYPE_PHP', 'sha256'),
    ],
];
