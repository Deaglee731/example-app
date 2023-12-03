<?php

namespace App\Jobs;

use App\Models\Account;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessingNumber implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $account;

    /**
     * Create a new job instance.
     */
    public function __construct(array $account)
    {
        $this->account = $account;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $start_time = microtime(true);

        logger()->info('Processing account' . data_get($this->account, 'account_id') . ' - number - ' . data_get($this->account, 'phone_number'));
        sleep(1);

        $end_time = microtime(true);
        $execution_time = $end_time - $start_time;
        logger()->info('Times', [$execution_time]);
    }
}
