<?php

use Ensi\LaravelPhpRdKafka\KafkaFacade;
use Ensi\LaravelPhpRdKafkaProducer\HighLevelProducer;
use Ensi\LaravelPhpRdKafkaProducer\Tests\Factories\HighLevelProducerFactory;
use RdKafka\Producer;

test('producer is instantiable', function () {
    expect(HighLevelProducerFactory::new()->make())->toBeObject();
});

test('producer can push middleware', function () {
    $producer = HighLevelProducerFactory::new()->make();
    $producer->pushMiddleware('TestMiddleware::class');
    $producer->pushMiddleware('TestMiddleware2::class');
    $producer->pushMiddleware('TestMiddleware::class');
    expect($producer->collectMiddleware())->toBe(['TestMiddleware::class', 'TestMiddleware2::class']);
});

test('producer uses global middleware in the beginning', function () {
    config()->set('kafka-producer.global_middleware', ['GlobalMiddleware::class']);

    $producer = HighLevelProducerFactory::new()->make();
    $producer->pushMiddleware('TestMiddleware::class');
    expect($producer->collectMiddleware())->toBe(['GlobalMiddleware::class', 'TestMiddleware::class']);
});

test('undefined topic throws exception', function () {
    HighLevelProducerFactory::new()->make('not-registered-topic-key');
})->throws(InvalidArgumentException::class);

test('producer is singleton', function () {
    $stub = new class ('default') extends HighLevelProducer {
        public function getProducer(): Producer
        {
            return $this->producer;
        }
    };

    expect($stub->getProducer())->toBe(KafkaFacade::producer('default'));
});
