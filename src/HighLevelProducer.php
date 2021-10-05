<?php

namespace Ensi\LaravelPhpRdKafkaProducer;

use Ensi\LaravelPhpRdKafka\KafkaManager;
use Ensi\LaravelPhpRdKafkaProducer\Exceptions\KafkaProducerException;
use RdKafka\Producer;
use RdKafka\ProducerTopic;

class HighLevelProducer
{
    protected Producer $producer;

    protected ProducerTopic $topic;

    public function __construct(
        protected string $topicName,
        ?string $producerName = null,
        protected int $flushTimeout = 5000,
        protected int $flushRetries = 5,
    )
    {
        $manager = resolve(KafkaManager::class);
        $this->producer =  is_null($producerName) ? $manager->producer() : $manager->producer($producerName);
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

    /**
     * @throws KafkaProducerException
     */
    public function sendOne(string $message): void
    {
        $this->topic->produce(RD_KAFKA_PARTITION_UA, 0, $message);
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
            $this->topic->produce(RD_KAFKA_PARTITION_UA, 0, $message);
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
}