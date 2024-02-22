<?php

namespace JibayMcs\DiscordErrorLogger;

use Filament\Contracts\Plugin;
use Filament\Panel;

class DiscordErrorLoggerPlugin implements Plugin
{
    protected string $siteId;
    protected string $token;
    private string $guildId;
    private string $api_url;

    public function getId(): string
    {
        return 'discord-error-logger';
    }

    public function register(Panel $panel): void
    {
        //
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function siteId(string $siteId): static
    {
        $this->siteId = $siteId;
        return $this;
    }

    public function getSiteId(): string
    {
        return $this->siteId;
    }

    public function token(string $token): static
    {
        $this->token = $token;
        return $this;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function apiUrl(string $api_url): static
    {
        $this->api_url = $api_url;
        return $this;
    }

    public function getApiUrl(): string
    {
        return $this->api_url;
    }

    public function guildId(string $guildId): static
    {
        $this->guildId = $guildId;
        return $this;
    }

    public function getGuildId(): string
    {
        return $this->guildId;
    }
}
