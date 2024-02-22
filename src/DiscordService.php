<?php

namespace JibayMcs\DiscordErrorLogger;

use Illuminate\Support\Facades\Http;

class DiscordService
{
    protected $token;

    protected $api_url;

    public function __construct()
    {
        $this->token = 'SS2I:6a6240737332692d6469676974616c2e66723137303835323537323168b63a93f577cf59868c0e34f78f5c09';
        $this->api_url = 'http://127.0.0.1:8080';
    }

    public function sendMessage(string $route, array $content, string $method = 'post')
    {
        return Http::withHeaders([
            'Authorization' => 'Bearer '.$this->token,
            'API-Controller' => 'ErrorsController',
            'API-Project' => '3def3f18-4b61-4e8b-bb44-708accc7137e',
            'Content-Type' => 'application/json',
        ])->{$method}($this->api_url."{$route}", $content);
    }
}
