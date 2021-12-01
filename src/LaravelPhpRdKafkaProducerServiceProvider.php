<?php

namespace Ensi\LaravelPhpRdKafkaProducer;

use Illuminate\Support\ServiceProvider;

class LaravelPhpRdKafkaProducerServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom($this->packageBasePath("/../config/kafka-producer.php"), 'kafka-producer');
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                $this->packageBasePath("/../config/kafka-producer.php") => config_path("kafka-producer.php"),
            ], "kafka-producer-config");
        }
    }

    protected function packageBasePath(string $directory = null): string
    {
        if ($directory === null) {
            return __DIR__;
        }

        return __DIR__ . DIRECTORY_SEPARATOR . ltrim($directory, DIRECTORY_SEPARATOR);
    }
}
