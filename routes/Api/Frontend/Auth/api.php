<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'     => 'frontend',
    'namespace'  => 'App\Http\Controllers\Api\Frontend\Auth',
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
