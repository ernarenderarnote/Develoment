<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DateTime;
use Braintree_Transaction;
use Braintree_TransactionSearch;
use Braintree_Configuration;

use App\Models\Payment;

class BraintreeGetRefunds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:braintree-get-refunds';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get refunds from Braintree and set order statuses';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->line('--------------------------');

        $hourAgo = new DateTime();
        $hourAgo = $hourAgo->modify('-1 hour');

        $collection = Braintree_Transaction::search([
            Braintree_TransactionSearch::createdAt()->greaterThanOrEqualTo($hourAgo),
            Braintree_TransactionSearch::type()->is(Braintree_Transaction::CREDIT),
            Braintree_TransactionSearch::refund()->is(true)
        ]);

        $this->line('Found transactions: '.$collection->maximumCount());

        foreach($collection as $transaction) {
            $this->line('Processing transaction: '.$transaction->id);
            $payment = Payment::where([
                'transaction_id' => $transaction->refundedTransactionId
            ])->first();

            if ($payment && !$payment->isRefunded()) {

                if ($payment->order) {
                    $payment->order->refunded('Refunded in automatic mode');
                }

                $payment->status = Payment::STATUS_REFUNDED;
                $payment->save();

                $this->line('Transaction refunded');
            }
        }

        $this->line('Finished');
    }
}
