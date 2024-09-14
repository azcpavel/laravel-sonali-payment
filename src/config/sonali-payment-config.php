<?php 

    return [
        'do-migration' => false,
        'route-middleware' => ['web'],
        'route-prefix' => 'sonali-payment',
        'route-name-prefix' => 'sonali-payment',
        'test-mode' => env('SONALI_PAYMENT_TEST',true),
        'test-mode-url' => env('SONALI_PAYMENT_TEST_URL','https://spg.sblesheba.com:6314'),
        'test-mode-token' => env('SONALI_PAYMENT_TEST_TOKEN',''),
        'pord-mode-url' => env('SONALI_PAYMENT_PORD_URL',''),
        'pord-mode-token' => env('SONALI_PAYMENT_PORD_TOKEN',''),
    ];