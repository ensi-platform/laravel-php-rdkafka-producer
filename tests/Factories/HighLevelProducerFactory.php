<?php

namespace Ensi\LaravelPhpRdKafkaProducer\Tests\Factories;

use Ensi\LaravelPhpRdKafkaProducer\HighLevelProducer;

class HighLevelProducerFactory
{
    public function make($topicName = 'default'): HighLevelProducer
    {
        return new HighLevelProducer($topicName);
    }

    public static function new(): static
    {
        return new static();
    }
}
