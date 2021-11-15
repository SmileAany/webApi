<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Robot extends Facade
{
    protected static function getFacadeAccessor():string
    {
        return 'Robot';
    }
}
