<?php

use Ensi\LaravelPhpRdKafkaProducer\Tests\TestCase;

uses(TestCase::class)
    ->in(__DIR__);

uses()
    ->beforeEach(function () {
        config()->set('kafka', require __DIR__ . "/kafka-config.php");
    })
    ->in(__DIR__);
