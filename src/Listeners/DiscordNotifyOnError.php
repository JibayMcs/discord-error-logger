<?php

namespace JibayMcs\DiscordErrorLogger\Listeners;

use Illuminate\Log\Events\MessageLogged;
use Illuminate\Support\Facades\Cache;
use JibayMcs\DiscordErrorLogger\DiscordService;

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

        $channelId = 1209508518051323914;

        if (isset($event->context['exception'])) {
            $exception = $event->context['exception'];

            $errorHash = md5($exception->getMessage().$exception->getFile());

            $cacheKey = 'error_mailer_'.$errorHash;
            $coolDownPeriod = 15;

            $ideScheme = 'phpstorm';
            $pathToHide = base_path();
            $projectPath = base_path();
            $hidePlaceholder = '';

            if (! Cache::has($cacheKey)) {
                $embeds = [
                    $this->exceptionToMarkdown(
                        exception: $exception,
                        pathToHide: $pathToHide,
                        projectPath: $projectPath,
                        hidePlaceholder: $hidePlaceholder,
                        ideScheme: $ideScheme
                    ),
                ];

                $filePath = $exception->getFile();
                $fileLine = $exception->getLine();
                $ideUrl = $ideScheme.'://open?file='.($projectPath.$filePath).'&line='.$fileLine;

                $ideUrl = '['.basename($exception->getFile())."]($ideUrl)";

                $components = [
                    [
                        'type' => 1,
                        'components' => [
                            [
                                'type' => 2,
                                'style' => 3,
                                'label' => 'Fixed',
                                'custom_id' => 'click_me',
                            ],
                        ],
                    ],
                ];

                $response = $this->service->sendMessage(
                    channelId: $channelId,
                    content: $ideUrl,
                    embeds: $embeds,
                    components: $components
                );

                if ($response->successful()) {
                    Cache::put($cacheKey, true, now()->addMinutes($coolDownPeriod));
                } else {
                    dd($response->body());
                }
            }
        }
    }

    public function exceptionToMarkdown($exception, $pathToHide, $projectPath, string $hidePlaceholder = '', string $ideScheme = 'phpstorm'): array
    {
        // Remplacer le chemin par un placeholder générique
        $genericPathPlaceholder = $hidePlaceholder;

        // Informations de base de l'exception
        $title = 'Exception: '.get_class($exception);
        $description = '**Message:** '.str_replace($pathToHide, $genericPathPlaceholder, $exception->getMessage())."\n".
            '**Fichier:** '.str_replace($pathToHide, $genericPathPlaceholder, $exception->getFile())."\n".
            '**Ligne:** '.$exception->getLine();

        // Générer et nettoyer la trace de la pile, limitant aux 11 premières entrées (de #0 à #10)
        $stackTraceLines = explode("\n", $exception->getTraceAsString());
        $filteredStackTraceLines = array_slice($stackTraceLines, 0, 11); // Garde seulement les 11 premières lignes
        $cleanStackTrace = implode("\n", array_map(function ($line) use ($pathToHide, $genericPathPlaceholder) {
            return str_replace($pathToHide, $genericPathPlaceholder, $line);
        }, $filteredStackTraceLines));

        // Description supplémentaire avec la trace de la pile filtrée
        $description .= "\n**Trace de la pile (filtrée):**\n```".$cleanStackTrace.'```';

        // Créer le tableau d'embed pour Restcord
        $embed = [
            'title' => substr($title, 0, 256), // Limite de 256 caractères pour le titre
            'description' => substr($description, 0, 2000), // Limite de 2048 caractères pour la description
            'color' => 0xFF0000, // Couleur rouge pour indiquer une erreur
            'timestamp' => date('c'), // Timestamp actuel
            //            "url" => $ideUrl,
            'footer' => [
                'text' => 'Exception Handler',
            ],
            'fields' => [
                [
                    'name' => 'Conseil',
                    'value' => "Vérifiez la trace de la pile pour identifier la source de l'exception.",
                ],
            ],
        ];

        return $embed;
    }
}
