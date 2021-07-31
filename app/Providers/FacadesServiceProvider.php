<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Notification\MessageService;

class FacadesServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('Message',MessageService::class);
    }

    public function boot()
    {
    }
}