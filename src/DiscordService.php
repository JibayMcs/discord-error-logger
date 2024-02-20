<?php

namespace JibayMcs\DiscordErrorLogger;

use Discord\Discord;
use Discord\Exceptions\IntentException;
use Illuminate\Support\Facades\Http;
use RestCord\DiscordClient;

class DiscordService
{

    protected $token;

    protected $api_url;

    public function __construct()
    {
        $this->token = config('discord-error-logger.token');
        $this->api_url = "https://discord.com/api/";
    }

    public function sendMessage(string $channelId, string $content, ?array $embeds = null, ?array $components = null)
    {
        $message = [
            'channel.id' => $channelId,
            'content' => $content,
        ];

        if($embeds) {
            $message['embeds'] = $embeds;
        }

        if($components) {
            $message['components'] = $components;
        }


        return Http::withHeaders([
            'Authorization' => 'Bot ' . $this->token,
            'Content-Type' => 'application/json',
        ])->post($this->api_url . "channels/{$channelId}/messages", $message);
    }
}
