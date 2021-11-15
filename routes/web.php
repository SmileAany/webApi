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

    $service = new robotService();

    throw new \Exception('的','500');

    try {

    }catch (\Exception $exception){
        dd(1111);

        dd($exception->__toString());

        dd($service->send('exception_robot',$exception));
    }



});
