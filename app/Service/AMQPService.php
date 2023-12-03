<?php

namespace App\Service;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Queue\Queue;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class AMQPService
{
    /**
     * @var AMQPStreamConnection
     */
    protected AMQPStreamConnection $connection;

    /**
     * @throws BindingResolutionException
     */
    public function __construct()
    {
        $this->connection = app()->make(AMQPStreamConnection::class)->with([
            config('rabbitmq.host'),
            config('rabbitmq.port'),
            config('rabbitmq.user'),
            config('rabbitmq.password'),
        ]);
    }


}
