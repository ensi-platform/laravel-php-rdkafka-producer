<?php

namespace Ensi\LaravelPhpRdKafkaProducer;

use Illuminate\Support\ServiceProvider;

class LaravelPhpRdKafkaProducerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom($this->packageBasePath("/../config/kafka-producer.php"), 'kafka-producer');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                $this->packageBasePath("/../config/kafka-producer.php") => config_path("kafka-producer.php"),
            ], "kafka-producer-config");
        }
    }

    protected function packageBasePath(?string $directory = null): string
    {
        if ($directory === null) {
            return __DIR__;
        }

        return __DIR__ . DIRECTORY_SEPARATOR . ltrim($directory, DIRECTORY_SEPARATOR);
    }
}
