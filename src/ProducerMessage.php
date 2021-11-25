<?php

namespace Ensi\LaravelPhpRdKafkaProducer;

class ProducerMessage
{
    /**
     * See https://arnaud.le-blanc.net/php-rdkafka-doc/phpdoc/rdkafka-producertopic.producev.html
     */
    public function __construct(
        public string $payload,
        public ?string $key = null,
        public ?array $headers = null,
        public ?int $timestampMs = null,
        public ?string $opaque = null,
    ) {
    }
}
