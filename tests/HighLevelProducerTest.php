<?php

use Ensi\LaravelPhpRdKafkaProducer\Tests\HighLevelProducerFactory;

test('producer is instantiable', function () {
    expect(HighLevelProducerFactory::new()->make())->toBeObject();
});

test('producer can push middleware', function () {
    $producer = HighLevelProducerFactory::new()->make();
    $producer->pushMiddleware('TestMiddleware::class');
    $producer->pushMiddleware('TestMiddleware2::class');
    $producer->pushMiddleware('TestMiddleware::class');
    expect($producer->collectMiddleware())->toBeArray(['TestMiddleware::class', 'TestMiddleware2::class']);
});

test('producer uses global middleware in the begining', function () {
    config()->set('kafka-producer.global_middleware', ['GlobalMiddleware::class']);

    $producer = HighLevelProducerFactory::new()->make();
    $producer->pushMiddleware('TestMiddleware::class');
    expect($producer->collectMiddleware())->toBeArray(['GlobalMiddleware::class', 'TestMiddleware::class']);
});
