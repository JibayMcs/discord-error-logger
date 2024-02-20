<?php

namespace JibayMcs\DiscordErrorLogger;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Log\Events\MessageLogged;
use JibayMcs\DiscordErrorLogger\Listeners\DiscordNotifyOnError;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        MessageLogged::class => [
            DiscordNotifyOnError::class,
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}
