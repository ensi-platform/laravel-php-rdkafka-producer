# Laravel PHP Rdkafka Producer

Opiniated High Level producer for [ensi/laravel-phprdkafka](https://github.com/ensi-platform/laravel-php-rdkafka)

## Installation

Firstly, you have to install and configure [ensi/laravel-phprdkafka](https://github.com/ensi-platform/laravel-php-rdkafka)

Then,
```bash
composer require ensi/laravel-phprdkafka-producer
```

## Usage

Send a single message:

```php
use Ensi\LaravelPhpRdKafkaProducer\HighLevelProducer;

(new HighLevelProducer($topicName))->sendOne($messageString);
```

Send several messages at once:

```php
(new HighLevelProducer($topicName))->sendMany([$message1String, $message2String]);
```

All options with defaults:

```php

use Ensi\LaravelPhpRdKafkaProducer\HighLevelProducer;
use Ensi\LaravelPhpRdKafkaProducer\Exceptions\KafkaProducerException;

$producer = new HighLevelProducer(
    topicName: $topicName, 
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

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
