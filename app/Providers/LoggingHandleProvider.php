<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Log\Events\MessageLogged;

class LoggingHandleProvider extends ServiceProvider
{
    public function register()
    {

    }

    public function boot()
    {
        if (config('logging.notice') !== false){
            Log::listen(function (MessageLogged $logger){
                if (in_array($logger->level,['emergency','alert','critical','error','warning'])){
                    $request = request()->all();

                    $message = $logger->message;
                    $context = $logger->context;
                }
            });
        }
    }
}