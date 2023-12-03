<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessingNumber;
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
        $data = $this->randomDataGenerator->generateRandomData(10000);

        collect($array)->groupBy('account_id')->map(function ($accounts) {
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
        $data = $this->randomDataGenerator->generateRandomData(10000);

        $exchangeName = 'consistent_hashing_exchange';
        $this->rabbitMqManager->declareConsistentHashingExchange($exchangeName);

        collect($data)->groupBy('account_id')->map(function ($accounts) use ($exchangeName) {
            collect($accounts)->map(function ($account) use ($exchangeName) {
                //Реализация на другом exchange
                $routingKey = 'sync-account - ' . $account['account_id'];
                dispatch(new ProcessingNumber($account))->onQueue( $routingKey);
                sleep(1);
            });
        });
    }
}
