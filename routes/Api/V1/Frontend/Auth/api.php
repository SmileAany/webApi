<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('V1',[
    'namespace'=>'App\Http\Controllers\Api\V1\Frontend\Auth'
],function ($api){
    $api->group([
        'prefix' => 'frontend'
    ],function ($api){
        $api->group([
            'prefix' => 'auth'
        ],function ($api){
            $api->post('login','AuthController@login')
                ->name('frontend.auth.login.post');

            $api->put('logout','AuthController@logout')
                ->middleware('jwt.token')
                ->name('frontend.auth.logout.put');

            $api->get('test','AuthController@test');
        });
    });
});