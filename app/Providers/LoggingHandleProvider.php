<?php

namespace App\Providers;

use Illuminate\Support\Facades\Http;
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
                    if (config('robot.status')) {
                        $url = config('robot.robot_webhook_url');

                        if (!empty($url) && is_string($url)) {
                            Http::post($url,[
                                'msgtype'  => 'markdown',
                                'markdown' => [
                                    'content' => "<font color=\"red\">日志提醒，请相关同事核查</font> \n日志内容:".$logger->message
                                ]
                            ]);
                        }
                    }
                }
            });
        }
    }
}