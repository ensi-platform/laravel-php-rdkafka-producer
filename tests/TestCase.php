<?php

namespace Ensi\LaravelPhpRdKafkaProducer\Tests;

use Ensi\LaravelPhpRdKafka\LaravelPhpRdKafkaServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected $loadEnvironmentVariables = true;

    protected function getPackageProviders($app): array
    {
        return [
            LaravelPhpRdKafkaServiceProvider::class,
        ];
    }
}
