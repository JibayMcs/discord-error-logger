<?php

namespace JibayMcs\DiscordErrorLogger;

use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Illuminate\Log\Events\MessageLogged;
use JibayMcs\DiscordErrorLogger\Listeners\DiscordNotifyOnError;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use JibayMcs\DiscordErrorLogger\Commands\DiscordErrorLoggerCommand;

class DiscordErrorLoggerServiceProvider extends PackageServiceProvider
{
    public static string $name = 'discord-error-logger';

    public static string $viewNamespace = 'discord-error-logger';


    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
//            ->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile();
//                    ->publishMigrations()
//                    ->askToRunMigrations()
//                    ->askToStarRepoOnGitHub('jibaymcs/discord-error-logger');
            });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        /*if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
        }*/

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        /*if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }*/
    }

    public function packageRegistered(): void
    {
        $this->app->register(EventServiceProvider::class);
    }

    public function packageBooted(): void
    {
        // Asset Registration
        /*FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        // Icon Registration
        FilamentIcon::register($this->getIcons());

        // Handle Stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/discord-error-logger/{$file->getFilename()}"),
                ], 'discord-error-logger-stubs');
            }
        }

        // Testing
        Testable::mixin(new TestsDiscordErrorLogger());*/
    }

    protected function getAssetPackageName(): ?string
    {
        return 'jibaymcs/discord-error-logger';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            // AlpineComponent::make('discord-error-logger', __DIR__ . '/../resources/dist/components/discord-error-logger.js'),
            Css::make('discord-error-logger-styles', __DIR__ . '/../resources/dist/discord-error-logger.css'),
            Js::make('discord-error-logger-scripts', __DIR__ . '/../resources/dist/discord-error-logger.js'),
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            DiscordErrorLoggerCommand::class,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getIcons(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [
            'create_discord-error-logger_table',
        ];
    }
}
