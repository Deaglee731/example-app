<?php

namespace App\Service;

use Illuminate\Support\Arr;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMqManager
{
    protected $connection;
    protected $channel;

    public function __construct()
    {
        $config = Arr::get(config('queue.connections.rabbitmq.hosts'), '0', []);
        $this->connection = new AMQPStreamConnection(
            data_get($config, 'host'),
            data_get($config, 'port'),
            data_get($config, 'user'),
            data_get($config, 'password'));
        $this->channel = $this->connection->channel();
    }

    public function declareConsistentHashingExchange($exchangeName)
    {
        $this->channel->exchange_declare(
            $exchangeName,
            'x-consistent-hash',
            false,
            true,
            false
        );
    }

    public function publishToExchange($exchangeName, $routingKey, $message)
    {
        $msg = new AMQPMessage($message);
        $this->channel->basic_publish($msg, $exchangeName, $routingKey);
    }

    public function closeConnection()
    {
        $this->channel->close();
        $this->connection->close();
    }
}
