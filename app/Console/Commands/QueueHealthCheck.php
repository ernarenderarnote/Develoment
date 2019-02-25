<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Response;
use Bugsnag;
use Exception;
use Illuminate\Console\Command;
use JKatzen\QueueMonitor\QueueStatus;

class QueueHealthCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:health-check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Laravel queue health';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
		$queueStatus = QueueStatus::get('default');
        
        // if queue execution delayed for 15 minutes
        // then presume that queue hanged up
        if (
            $queueStatus
            && $queueStatus->getStatus() == 'pending'
            && $queueStatus->getStartTime()->lt(Carbon::now()->subMinutes(15))
        ) {
            $e = new Exception('Health check job is waiting for more than 15 minutes. Queue is probably hanged up, please check');
            Bugsnag::notifyException($e);
        }
    }
}
