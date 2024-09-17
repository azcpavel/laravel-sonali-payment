<?php
use Illuminate\Support\Facades\Route;
$sonali_route_prifix = config('sonali-payment-config.route-prefix');
Route::group([
                'prefix' => $sonali_route_prifix, 
                'middleware' => config('sonali-payment-config.route-middleware')
            ], 
            function() use($sonali_route_prifix){
    $controller_namespace = 'Exceptio\\SonaliPayment\\Http\\Controllers\\';
    Route::get('/',$controller_namespace.'SonaliPaymentController@hello')->name($sonali_route_prifix.'.hello');

    Route::get('test',$controller_namespace.'SonaliPaymentController@test')->name($sonali_route_prifix.'.test');
    
    Route::post('test-response',$controller_namespace.'SonaliPaymentController@test_response')->name($sonali_route_prifix.'.test_response');

    Route::post('test-ipn',$controller_namespace.'SonaliPaymentController@test_ipn')->name($sonali_route_prifix.'.test_ipn');
});
