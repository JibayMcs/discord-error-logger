<?php

namespace JibayMcs\DiscordErrorLogger\Listeners;

use Exception;
use Illuminate\Log\Events\MessageLogged;
use Illuminate\Support\Facades\Cache;
use JibayMcs\DiscordErrorLogger\DiscordService;
use Spatie\FlareClient\Flare;

class DiscordNotifyOnError
{
    protected DiscordService $service;

    public function __construct()
    {
        $service = new DiscordService();
        $this->service = $service;
    }

    public function handle(MessageLogged $event)
    {
        $coolDownPeriod = 0;

        if (isset($event->context['exception'])) {
            $exception = $event->context['exception'];

            $errorHash = md5($exception->getMessage() . $exception->getFile());

            $cacheKey = 'error_mailer_' . $errorHash;
            $coolDownPeriod = 15;

            if (!Cache::has($cacheKey)) {
                $response = $this->service->sendMessage('/api/errors/new', $this->exceptionToJson($exception, 1209854464362684416));

                if ($response->successful()) {
                    Cache::put($cacheKey, true, now()->addMinutes($coolDownPeriod));
                } else {
                    dd($response->body());
                }
            }
        }
    }

    private function exceptionToJson(Exception $exception, int $channelId): array
    {
        //get the first trace of the exception where the class path is app/

        $trace = collect($exception->getTrace())->map(function ($trace) {
            return [
                'file' => $trace['file'] ?? '',
                'line' => $trace['line'] ?? '',
                'function' => $trace['function'] ?? '',
                'class' => $trace['class'] ?? '',
            ];
        });

        // Partitionner la collection en deux : ceux hors de "vendor" et ceux dans "vendor"
        [$nonVendorTraces, $vendorTraces] = $trace->partition(function ($trace) {
            return !str_contains($trace['file'], '/vendor/');
        });

        // Concaténer les collections pour mettre les traces non-vendor en tête
        $orderedTraces = $nonVendorTraces->concat($vendorTraces)->all();

        return [
            'channel_id' => $channelId,
            'env' => $this->constructEnvInformation(),
            'user' => $this->getUserInformation(),
            'base_path' => base_path(),
            'date' => now()->toIso8601String(),
            'error' => [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'stack_trace' => $orderedTraces,
            ],
        ];
    }

    private function constructEnvInformation(): array
    {
        return [
            'env' => config('app.env'),
            'php' => phpversion(),
            'laravel' => app()->version(),
        ];
    }

    private function getUserInformation(): ?array
    {
        if (auth()->check()) {
            return [
                'id' => auth()->id(),
                'email' => auth()->user()->email,
            ];
        }

        return null;
    }
}
