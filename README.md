# Laravel PHP Rdkafka Producer

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ensi/laravel-phprdkafka-producer.svg?style=flat-square)](https://packagist.org/packages/ensi/laravel-phprdkafka-producer)
[![Tests](https://github.com/ensi-platform/laravel-php-rdkafka/actions/workflows/run-tests.yml/badge.svg?branch=master)](https://github.com/ensi-platform/laravel-php-rdkafka/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/ensi/laravel-phprdkafka-producer.svg?style=flat-square)](https://packagist.org/packages/ensi/laravel-phprdkafka-producer)

Opiniated High Level producer for [ensi/laravel-phprdkafka](https://github.com/ensi-platform/laravel-php-rdkafka)

## Installation

Firstly, you have to install and configure [ensi/laravel-phprdkafka](https://github.com/ensi-platform/laravel-php-rdkafka)

Then,
```bash
composer require ensi/laravel-phprdkafka-producer
```

Publish the config file if you need it:

```bash
php artisan vendor:publish --provider="Ensi\LaravelPhpRdKafkaProducer\LaravelPhpRdKafkaProducerServiceProvider" --tag="kafka-producer-config"
```

## Version Compatibility

| Laravel rdkakfa-producer | Laravel                              | PHP  | ensi/laravel-phprdkafka |
|--------------------------|--------------------------------------|------|-------------------------|
| ^0.1.0                   | ^7.x \|\| ^8.x                       | ^8.0 | ^0.1.4                  |
| ^0.2.0                   | ^7.x \|\| ^8.x                       | ^8.0 | ^0.1.4                  |
| ^0.2.1                   | ^7.x \|\| ^8.x                       | ^8.0 | ^0.2                    |
| ^0.2.3                   | ^8.x \|\| ^9.x                       | ^8.0 | ^0.2                    |
| ^0.3.0                   | ^8.x \|\| ^9.x                       | ^8.0 | ^0.3.0                  |
| ^0.3.2                   | ^8.x \|\| ^9.x \|\| ^10.x            | ^8.0 | ^0.3.0                  |
| ^0.3.3                   | ^8.x \|\| ^9.x \|\| ^10.x \|\| ^11.x | ^8.0 | ^0.3.4                  |
| ^0.4.0                   | ^9.x \|\| ^10.x \|\| ^11.x           | ^8.1 | ^0.4.0                  |

## Basic Usage

Send a single message:

```php
use Ensi\LaravelPhpRdKafkaProducer\HighLevelProducer;

(new HighLevelProducer($topicKey))->sendOne($messageString);
```

Send several messages at once:

```php
(new HighLevelProducer($topicKey))->sendMany([$message1String, $message2String]);
```

All options with defaults:

```php

use Ensi\LaravelPhpRdKafkaProducer\HighLevelProducer;
use Ensi\LaravelPhpRdKafkaProducer\Exceptions\KafkaProducerException;

$producer = new HighLevelProducer(
    topicKey: $topicKey, 
    producerName: 'some-producer-from-ensi/laravel-phprdkafka-config', 
    flushTimeout: 5000, // ms
    flushRetries: 5,
);

try {
    $producer
        ->setFlushTimeout(10000)
        ->setFlushRetries(10)
        ->sendOne($messageString);
} catch (KafkaProducerException $e) {
    //...
}

```


### Middleware

You can add middleware globally via config or locally for specific Producer:

```php
$producer->pushMiddleware(SomeMiddleware::class)
        ->sendOne($messageString);
```

Middleware example:

```php

use Closure;
use Ensi\LaravelPhpRdKafkaProducer\ProducerMessage;

class SomeMiddleware
{
    public function handle(ProducerMessage $message, Closure $next): mixed
    {
        $message->headers = $message->headers ?: [];
        $message->headers['Header-Name'] = 'Header Value';

        return $next($message);
    }
}

```

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

### Testing

1. composer install
2. composer test

## Security Vulnerabilities

Please review [our security policy](.github/SECURITY.md) on how to report security vulnerabilities.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
