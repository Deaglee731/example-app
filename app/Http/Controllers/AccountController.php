<?php

namespace App\Http\Controllers;

use AMQPConnection;
use App\Jobs\ProcessingNumber;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use App\Http\Requests\AccountRequest;
use VladimirYuldashev\LaravelQueueRabbitMQ\Queue\RabbitMQQueue;

class AccountController extends Controller
{
    protected RabbitMQQueue $rabbitMQQueue;

    public function __construct(RabbitMQQueue $rabbitMQQueue)
    {
        $this->rabbitMQQueue = $rabbitMQQueue;
    }

    /**
     * @param AccountRequest $request
     * @return void
     * @throws \Exception
     */
    public function index(AccountRequest $request): void
    {
        $jsonData = $request->json()->all();

        collect($jsonData)->groupBy('account_id')->map(function ($accounts) {
            collect($accounts)->map(function ($account) {
               dispatch(new ProcessingNumber($account))->onQueue( 'sync-account - ' . $account['account_id']);
               sleep(1);
            });
        });
    }
}
