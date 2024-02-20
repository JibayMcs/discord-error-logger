<?php

namespace JibayMcs\DiscordErrorLogger\Commands;

use Illuminate\Console\Command;

class DiscordErrorLoggerCommand extends Command
{
    public $signature = 'discord-error-logger';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
