<?php

use Illuminate\Support\Facades\Route;

use App\Facades\Message;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
//    Message::email('ywjmylove@163.com',[
//        'templateId' => 1,
//        'subject'    => '注册',
//        'data'       => [
//
//        ]
//    ]);

    Message::sms('15102750714',[
        'data' => [
            '注册验证',
            '5438'
        ]
    ]);
});
