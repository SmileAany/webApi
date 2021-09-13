<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace'  => 'App\Http\Controllers\Api',
],function ($api){
    $api->group([

    ],function ($api){
        $api->post('file/upload','FileController@upload')
            ->name('file.upload.post');
    });
});
