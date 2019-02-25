<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DateTime;
use Braintree_Transaction;
use Braintree_TransactionSearch;
use Braintree_Configuration;

use App\Models\Payment;

class BraintreeDebug extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:braintree-debug';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->line('--------------------------');

        $collection = Braintree_Transaction::search([
            Braintree_TransactionSearch::customerId()->is('1')
        ]);

        $data = [];
        foreach($collection as $transaction) {

            $data[] = [
                'id' => $transaction->id,
                'status' => $transaction->status,
                'type' => $transaction->type,
                'amount' => $transaction->amount,
                'createdAt' => $transaction->createdAt->format('Y-m-d H:i:s'),
                'paymentId' => $transaction->customFields['payment_id'],
                'orderId' => $transaction->customFields['order_id']
            ];
        }

        $this->table([
            'id',
            'status',
            'type',
            'amount',
            'createdAt',
            'paymentId',
            'orderId'
        ], $data);

        $this->line('Finished');
    }
}
