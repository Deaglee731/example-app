<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessingNumber;
use App\Service\CustomWorker;
use App\Service\RabbitMqManager;
use App\Http\Requests\AccountRequest;
use App\Service\RandomDataGenerator;

class AccountController extends Controller
{
    protected RabbitMqManager $rabbitMqManager;

    protected RandomDataGenerator $randomDataGenerator;

    public function __construct(RabbitMqManager $rabbitMqManager, RandomDataGenerator $randomDataGenerator)
    {
        $this->rabbitMqManager = $rabbitMqManager;
        $this->randomDataGenerator = $randomDataGenerator;
    }

    /**
     * @param AccountRequest $request
     * @return void
     * @throws \Exception
     */
    public function rabbitMq(AccountRequest $request): void
    {
        $data = $this->randomDataGenerator->generateRandomData(50);

        collect($data)->groupBy('account_id')->map(function ($accounts) {
            collect($accounts)->map(function ($account) {
                //TODO Возможно стоить проверять на наличие пустой очереди и обрабатывать несколько аккаунтов в рамках 1 очереди.
                // ибо для 10к аккаунтов будет 10к воркеров, что дорого. Возможно фиксится фичами кролика
               dispatch(new ProcessingNumber($account))->onQueue( 'sync-account - ' . $account['account_id']);
               sleep(1);
            });
        });
    }

    /**
     * @param AccountRequest $request
     * @return void
     * @throws \Exception
     */
    public function rabbitMq2(AccountRequest $request): void
    {
        $data = $this->randomDataGenerator->generateRandomData(50);

        $exchangeName = 'consistent_hashing_exchange';
        $this->rabbitMqManager->declareConsistentHashingExchange($exchangeName);

        collect($data)->groupBy('account_id')->map(function ($accounts) use ($exchangeName) {
            $queues = collect($accounts)->pluck('account_id');

            collect($accounts)->map(function ($account) use ($exchangeName, $queues) {
                //Попытка 2 exchange https://habr.com/ru/articles/489086/

                //TODO не успель :c
                $job = new ProcessingNumber($account);
                $routingKey = $queues[crc32($job->account['account_id']) % count($queues)];

                dispatch(new ProcessingNumber($account))->onQueue( $routingKey);
                sleep(1);
            });
        });
    }
}
