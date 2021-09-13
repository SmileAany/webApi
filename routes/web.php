<?php

use Illuminate\Support\Facades\Route;

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

    $array = [
        'name' => [
            'age' => '12'
        ],
        'age' => [
            14
        ]
    ];


    dd(customer_one_array($array,function ($item, &$result){

        $result[] = $item . 123;
    }));



    $params = [
        'namae' => 'smile',
        'age'  => 12
    ];

    $str = '${name} 的年龄是 ${age}';

    dd(customer_analysis_string('{','}',$str,$params));

});


