<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'auth',
    'namespace' => 'App\Http\Controllers\Api\Frontend\Auth'
],function ($route){
    $route->post('login','AuthController@login');
});
