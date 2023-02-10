# Laravel PHP Rdkafka Producer

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

## Usage

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

## Testing

```bash
cp .env.example .env
vim .env # add real kafka credentials

composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
