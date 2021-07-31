<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Message extends Facade
{
    protected static function getFacadeAccessor():string
    {
        return 'Message';
    }
}