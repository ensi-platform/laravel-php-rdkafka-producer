<?php

namespace Ensi\LaravelPhpRdKafkaProducer;

use Ensi\LaravelPhpRdKafka\KafkaManager;
use Ensi\LaravelPhpRdKafkaProducer\Exceptions\KafkaProducerException;
use Illuminate\Pipeline\Pipeline;
use RdKafka\Producer;
use RdKafka\ProducerTopic;

class HighLevelProducer
{
    protected Producer $producer;

    protected ProducerTopic $topic;

    protected Pipeline $pipeline;

    protected $middleware = [];

    public function __construct(
        protected string $topicName,
        ?string $producerName = null,
        protected int $flushTimeout = 5000,
        protected int $flushRetries = 5,
    ) {
        $manager = resolve(KafkaManager::class);
        $this->pipeline = resolve(Pipeline::class);
        $this->producer = is_null($producerName) ? $manager->producer() : $manager->producer($producerName);
        $this->topic = $this->producer->newTopic($topicName);
    }

    public function setFlushTimeout(int $timeout): static
    {
        $this->flushTimeout = $timeout;

        return $this;
    }

    public function setFlushRetries(int $retries): static
    {
        $this->flushRetries = $retries;

        return $this;
    }

    public function pushMiddleware(string $middleware): static
    {
        if (array_search($middleware, $this->middleware) === false) {
            $this->middleware[] = $middleware;
        }

        return $this;
    }

    public function collectMiddleware(): array
    {
        return array_unique(array_merge(config('kafka-producer.global_middleware', []), $this->middleware));
    }

    /**
     * @throws KafkaProducerException
     */
    public function sendOne(string $message): void
    {
        $this->produceThroughMiddleware($message);
        $this->producer->poll(0);

        $code = $this->flush();
        $this->raiseExceptionOnErrorCode($code);
    }

    /**
     * @throws KafkaProducerException
     */
    public function sendMany(array $messages): void
    {
        foreach ($messages as $message) {
            $this->produceThroughMiddleware($message);
            $this->producer->poll(0);
        }

        $code = $this->flush();
        $this->raiseExceptionOnErrorCode($code);
    }

    protected function flush(): int
    {
        for ($flushRetries = 0; $flushRetries < $this->flushRetries; $flushRetries++) {
            $result = $this->producer->flush($this->flushTimeout);
            if (RD_KAFKA_RESP_ERR_NO_ERROR === $result) {
                return $result;
            }
        }

        return $result;
    }

    /**
     * @throws KafkaProducerException
     */
    protected function raiseExceptionOnErrorCode(int $code)
    {
        if (RD_KAFKA_RESP_ERR_NO_ERROR !== $code) {
            $topicName = $this->topic->getName();

            throw new KafkaProducerException(
                "Sending message to kafka topic=$topicName failed, error_code=$code"
            );
        }
    }

    protected function produceThroughMiddleware(string $payload): void
    {
        $middleware = $this->collectMiddleware();

        $this->pipeline
            ->send(new ProducerMessage($payload))
            ->through($middleware)
            ->then(function (ProducerMessage $message) {
                $this->topic->producev(
                    RD_KAFKA_PARTITION_UA,
                    0,
                    $message->payload,
                    $message->key,
                    $message->headers,
                    $message->timestampMs,
                    $message->opaque,
                );
            });
    }
}
