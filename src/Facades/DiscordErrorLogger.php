<?php

namespace JibayMcs\DiscordErrorLogger\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \JibayMcs\DiscordErrorLogger\DiscordErrorLogger
 */
class DiscordErrorLogger extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \JibayMcs\DiscordErrorLogger\DiscordErrorLogger::class;
    }
}
