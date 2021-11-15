<?php

namespace App\Providers;

use App\Services\robotService;
use Illuminate\Support\ServiceProvider;
use App\Services\Notification\MessageService;

class FacadesServiceProvider extends ServiceProvider
{
    public function register()
    {
        /**
         * 消息
         */
        $this->app->singleton('Message',MessageService::class);

        /**
         * 机器人提示语
         */
        $this->app->singleton('Robot',robotService::class);
    }

    public function boot()
    {
    }
}
