<?php

namespace Ensi\LaravelPhpRdKafkaProducer\Tests;

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
