<?php

namespace App\Models;

use Log;
use Bugsnag;
use Exception;
use Illuminate\Database\Eloquent\Model;
use League\Fractal;

use App\Components\Money;
use App\Components\Logger;

class Payment extends Base
{
    use \Culpa\Traits\Blameable;

    // user_id
    // gateway
    // amount
    // cardholder_name
    // transaction_id
    // status
    // created_at

    const GATEWAY_BRAINTREE = 'braintree';

    const STATUS_NEW    = 'new';
    const STATUS_FAILED = 'failed';
    const STATUS_SUCCEEDED = 'succeeded';
    const STATUS_REFUNDED = 'refunded';

    protected $table = 'payments';

    // blameable
    protected $blameable = [
        'created' => 'user_id'
    ];


    public function __construct(array $attributes = [])
    {
        $this->setRawAttributes(array_merge($this->attributes, [
          'status' => static::STATUS_NEW
        ]), true);
        parent::__construct($attributes);
    }

    /************
     * Mutators
     */

        public function setStatusAttribute($value)
        {
            if (!$this->status && !$value) {
                $this->attributes['status'] = static::STATUS_NEW;
            }
            else {
                $this->attributes['status'] = $value;
            }
        }

    /***********
     * Relations
     */

        public function user()
        {
            return $this->belongsTo(User::class, 'user_id');
        }

        public function order()
        {
            return $this->belongsTo(Order::class, 'order_id');
        }

        public function product()
        {
            return $this->belongsTo(Product::class, 'product_id');
        }

    /***********
     * Checks
     */

        public function isOwner($user)
        {
            return $user->isOwnerOf($this);
        }

        public function isPaid()
        {
            return $this->status == static::STATUS_SUCCEEDED;
        }

        public function isRefunded()
        {
            return $this->status == static::STATUS_REFUNDED;
        }

    /**************
     * Transformers
     */



    /**********
     * Counters
     */



    /*************
     * Decorators
     */

        public function amount()
        {
            return Money::i()->parse($this->amount);
        }


    /*********
     * Helpers
     */



    /***********
     * Functions
     */

        public static function payForOrder($user, $order)
        {
            $amount = Money::i()->amount($order->total());

            $payment = new static;
            $payment->gateway = static::GATEWAY_BRAINTREE;
            $payment->amount = $amount;
            $payment->user_id = $order->user_id;
            $payment->order_id = $order->id;
            $payment->save();

            $transaction = null;
            try {
                $result = $user->charge($amount, [
                    'taxAmount' => Money::i()->amount($order->taxTotal()),
                    'customFields' => [
                        'payment_id' => $payment->id,
                        'order_id' => $order->id
                    ]
                ]);
                $transaction = $result->transaction;

                $payment->status = static::STATUS_SUCCEEDED;
                $payment->transaction_id = $transaction->id;
                $payment->save();

                \Event::fire(new \App\Events\User\UserChargedEvent($user, $amount));
            }
            catch(Exception $e) {

                Logger::i(Logger::PAYMENTS)
                    ->error('Braintree charge error: '.$e->getMessage(), [
                        'payment' => $payment,
                        'transaction' => $transaction,
                        'user' => $user,
                    ]);

                Log::error($e);
                Bugsnag::notifyException($e);

                $payment->status = static::STATUS_FAILED;
                $payment->transaction_id = !empty($transaction) && !empty($transaction->id)
                    ? $transaction->id
                    : null;
                $payment->save();

                throw $e;
            }

            return $payment;
        }

        public static function payForPrepaidProduct($user, $product)
        {
            $amount = $product->template()->category->prepaid_amount;

            $payment = new static;
            $payment->gateway = static::GATEWAY_BRAINTREE;
            $payment->amount = $amount;
            $payment->user_id = $user->id;
            $payment->product_id = $product->id;
            $payment->save();

            $transaction = null;
            try {
                $result = $user->charge($amount, [
                    'customFields' => [
                        'payment_id' => $payment->id,
                        'product_id' => $product->id
                    ]
                ]);
                $transaction = $result->transaction;

                $payment->status = static::STATUS_SUCCEEDED;
                $payment->transaction_id = $transaction->id;
                $payment->save();

                \Event::fire(new \App\Events\User\UserChargedEvent($user, $amount));
            }
            catch(Exception $e) {

                Logger::i(Logger::PAYMENTS)
                    ->error('Braintree charge error: '.$e->getMessage(), [
                        'payment' => $payment,
                        'transaction' => $transaction,
                        'user' => $user,
                    ]);

                Log::error($e);
                Bugsnag::notifyException($e);

                $payment->status = static::STATUS_FAILED;
                $payment->save();
            }

            return $payment;
        }

    /*************
     * Collections
     */

        public static function getForUser($user)
        {
            return static::with('user')
                ->where('user_id', $user->id)
                ->get();
        }


}
