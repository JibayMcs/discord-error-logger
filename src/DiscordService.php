<?php

namespace JibayMcs\DiscordErrorLogger;

use Filament\Notifications\Notification;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\HtmlString;

class DiscordService
{
    protected $token;

    protected $api_url;

    public function __construct()
    {
        $this->token = DiscordErrorLoggerPlugin::get()->getToken();
        $this->api_url = DiscordErrorLoggerPlugin::get()->getApiUrl();
    }

    public function sendMessage(string $route, array $content, string $controller, string $method = 'post')
    {
        try {
            return Http::withHeaders([
                'Authorization' => 'Bearer '.$this->token,
                'API-Controller' => $controller,
                'API-Project' => DiscordErrorLoggerPlugin::get()->getSiteId(),
                'API-Project-Env' => config('app.env'),
                'API-Guild' => DiscordErrorLoggerPlugin::get()->getGuildId(),
                'Content-Type' => 'application/json',
            ])->{$method}($this->api_url."{$route}", $content);
        } catch (ConnectionException $e) {
            Notification::make('unable-to-connect-to-discord')
                ->warning()
                ->title('Unable to connect to Discord Bot Services')
                ->body(new HtmlString("Unable to connect to Discord Bot Services.\nPlease check your internet connection and try again."))
                ->send();

            return null;
        }
    }
}
