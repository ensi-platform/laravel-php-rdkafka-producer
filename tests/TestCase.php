<?php

namespace Ensi\LaravelPhpRdKafkaProducer\Tests;

use Ensi\LaravelPhpRdKafka\LaravelPhpRdKafkaServiceProvider;
use Ensi\LaravelPhpRdKafkaProducer\LaravelPhpRdKafkaProducerServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected $loadEnvironmentVariables = true;

    protected function getPackageProviders($app): array
    {
        return [
            LaravelPhpRdKafkaServiceProvider::class,
            LaravelPhpRdKafkaProducerServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('database.default', 'testing');

        config()->set('kafka.connections.default.topics', ['default' => 'test.domain.fact.default.1']);
    }
}
