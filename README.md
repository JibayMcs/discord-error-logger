# A FilamentPHP plugin to send exceptions of your project on Discord

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jibaymcs/discord-error-logger.svg?style=flat-square)](https://packagist.org/packages/jibaymcs/discord-error-logger)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/jibaymcs/discord-error-logger/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/jibaymcs/discord-error-logger/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jibaymcs/discord-error-logger/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/jibaymcs/discord-error-logger/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/jibaymcs/discord-error-logger.svg?style=flat-square)](https://packagist.org/packages/jibaymcs/discord-error-logger)



This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require jibaymcs/discord-error-logger
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="discord-error-logger-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="discord-error-logger-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="discord-error-logger-views"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$discordErrorLogger = new JibayMcs\DiscordErrorLogger();
echo $discordErrorLogger->echoPhrase('Hello, JibayMcs!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [JibayMcs](https://github.com/JibayMcs)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
