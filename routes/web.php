<?php

use Illuminate\Support\Facades\Route;

use App\Facades\Message;
use App\Services\robotService;
use App\Exceptions\NoticeException;

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
    try {
//        $service = new robotService();
//        dd($service->send('exception_robot',[
//            '异常提醒',
//            'ddd'
//        ]));

        throw new \Exception('大的呃');
    }catch (\Exception $exception) {
        NoticeException::handle($exception);
    }


dd(111);


});
