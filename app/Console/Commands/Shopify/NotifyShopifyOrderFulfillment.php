<?php

namespace App\Console\Commands\Shopify;

use Bugsnag;
use Exception;
use DateTime;

use Illuminate\Console\Command;

use App\Components\Shopify;
use App\Components\KZApi;
use App\Components\Logger;
use App\Models\Order;

class NotifyShopifyOrderFulfillment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:notify-shopify-order-fulfillment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send fulfilled orders to Shopify';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->line('--------------------------');
        $this->line('Starting '.$this->description);

        $orders = Order::whereHasMeta(Order::META_SHOPIFY_FULFILLMENT_DATA)
            ->whereDoesntHave('metafields', function($q) {
                $q->where('key', Order::META_SHOPIFY_FULFILLMENT_NOTIFIED_AT);
            })
            ->get();

        $this->line('Found orders: '.count($orders));

        foreach($orders as $order) {
            $this->line('---');
            $this->line('Processing order #'.$order->id);

            $order_fulfillment_data = $order->getMeta(Order::META_SHOPIFY_FULFILLMENT_DATA);
            $store_domain = $order->store->shopifyDomain();
            $access_token = $order->store->access_token;

            try {
                Shopify::i($store_domain, $access_token)
                    ->fulfillOrder($order->provider_order_id, $order_fulfillment_data);

                $order->setMeta(Order::META_SHOPIFY_FULFILLMENT_NOTIFIED_AT, new DateTime());
                $this->info('Notification sent');
            }
            catch(Exception $e) {
                $logMetadata = [
                    'order' => $order,
                    'order_fulfillment_data' => $order_fulfillment_data
                ];
                Logger::i(Logger::API_KZ_ORDER_FULFILLMENTS_SHOPIFY)
                    ->error('fulfillOrder shopify error', $logMetadata);
                Bugsnag::registerCallback(function ($report) use($logMetadata) {
                    $report->setMetaData($logMetadata);
                });
                Bugsnag::notifyException($e);

                $order->setMeta(Order::META_SHOPIFY_FULFILLMENT_ERROR, $e->getMessage());

                // if order was fulfilled before
                if (stristr($e->getMessage(), '422 Unprocessable Entity')) {
                    $order->setMeta(Order::META_SHOPIFY_FULFILLMENT_NOTIFIED_AT, new DateTime());
                    $this->line('Notification seems to be sent earlier: '.$e->getMessage());
                    $this->info('Notification not sent');
                }
                else {
                    $this->error('Cannot process notification: '.$e->getMessage());
                    $this->error('Notification not sent');
                }
            }
        }

        $this->line('Finished');
    }
}
