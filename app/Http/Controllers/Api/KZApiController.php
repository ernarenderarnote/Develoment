<?php
/**
 * Created by PhpStorm.
 * User: konstantin
 * Date: 8/31/16
 * Time: 2:15 PM
 */

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Exception;
use Lib\Crypt\Crypt;

use App\Components\Logger;
use App\Components\Shopify;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\ProductModel;

class KZApiController extends BaseController
{
    const DO_RETRY_KZ_ORDERS_NOTIFY = 'retry-kz-orders-notify';

    /**
     * Expects requests with data:
     * $raw_data = [
     *    'order_id' => 10001,
     *    'tracking_number' => 'TN123',
     *    'item_ids' => [
     *        20002
     *    ]
     * ];
     */
    public function fulfillOrder(Request $request)
    {
        $raw_data = $request->get('data');

        $logMetadata = [
            'raw_data' => $raw_data,
            'requestParams' => $request->all(),
            'requestBody' => $request->getContent()
        ];

        Logger::i(Logger::API_KZ_ORDER_FULFILLMENTS)
            ->info('fulfillOrder info', $logMetadata);

        try {
            $crypt = new Crypt();

            $crypt->setKey(env('KZ_API_SECRET', ''));
            $crypt->setComplexTypes(TRUE);
            $crypt->setData($raw_data);
            $decrypted = $crypt->decrypt();
        }
        catch(Exception $e) {
            Logger::i(Logger::API_KZ_ORDER_FULFILLMENTS)
                ->error('fulfillOrder error', $logMetadata);
            \Bugsnag::registerCallback(function ($report) use($logMetadata) {
                $report->setMetaData($logMetadata);
            });
            \Bugsnag::notifyException($e);

            return abort(422, 'Cannot decrypt data: '.$e->getMessage());
        }

        Logger::i(Logger::API_KZ_ORDER_FULFILLMENTS)
            ->info('fulfillOrder info descrypted', [
                'decrypted' => $decrypted
            ]);

        $order = null;
        $order_fulfillment_data = null;
        try {
            $line_items = [];
            $order_id = $decrypted['order_id'];
            $order = Order::where('id', $order_id)
                ->first();

            if ($order->isVendorOrder()) {
                $variants = $order->variants()
                    ->whereIn('id', $decrypted['item_ids'])
                    ->get();

                foreach ($variants as $variant) {
                    $line_items[] = [
                        'id'       => $variant->lineItemId(),
                        'quantity' => $variant->quantity()
                    ];
                }

                $order_fulfillment_data = [
                    'tracking_number' => $decrypted['tracking_number'],
                    'line_items'      => $line_items,
                    'notify_customer' => true
                ];

                $order->setMeta(Order::META_SHOPIFY_FULFILLMENT_DATA, $order_fulfillment_data);

                // NOTICE: Shopify notification will be sent from the cron
                // with NotifyShopifyOrderFulfillment command
            }
        }
        catch(Exception $e) {
            $logMetadata = array_merge($logMetadata, [
                'order_id' => $order_id,
                'order' => $order,
                'order_fulfillment_data' => $order_fulfillment_data
            ]);
            Logger::i(Logger::API_KZ_ORDER_FULFILLMENTS)
                ->error('fulfillOrder error', $logMetadata);
            \Bugsnag::registerCallback(function ($report) use($logMetadata) {
                $report->setMetaData($logMetadata);
            });
            \Bugsnag::notifyException($e);

            return abort(422, 'Cannot process order');
        }

        $order->fulfilled($decrypted['tracking_number']);

        return response()->json(['success' => true]);
    }

    /**
     * Expects POST request with form data:
     * data -> encrypted( ['do' => 'retry-kz-orders-notify'] )
     */
    public function retryKZOrdersNotify(Request $request)
    {
        $raw_data = $request->input('data');
        //$raw_data = ['do' => 'retry-kz-orders-notify'];

        $logMetadata = [
            'raw_data' => $raw_data,
            'requestParams' => $request->all(),
            'requestBody' => $request->getContent()
        ];

        $decrypted = null;
        try {
            $crypt = new Crypt();

            $crypt->setKey(env('KZ_API_SECRET', ''));
            $crypt->setComplexTypes(TRUE);
            $crypt->setData($raw_data);
            $decrypted = $crypt->decrypt();
        }
        catch(Exception $e) {
            Logger::i(Logger::API_KZ_ORDERS)
                ->error('retryKZOrdersNotify error', $logMetadata);

            \Bugsnag::registerCallback(function ($report) use($logMetadata) {
                $report->setMetaData($logMetadata);
            });
            \Bugsnag::notifyException($e);
            return abort(422, 'Cannot process order');
        }

        if (
            !$decrypted
            || $decrypted['do'] != static::DO_RETRY_KZ_ORDERS_NOTIFY
        ) {
            $logMetadata['decrypted'] = $decrypted;

            Logger::i(Logger::API_KZ_ORDERS)
                ->error('retryKZOrdersNotify error', $logMetadata);

            \Bugsnag::registerCallback(function ($report) use($logMetadata) {
                $report->setMetaData($logMetadata);
            });
            \Bugsnag::notifyException(new Exception('retryKZOrdersNotify error'));

            return response()->json(['success' => false]);
        }

        \Artisan::call('kz:notify-orders');
        return response()->json(['success' => true]);
    }

    /**
     * Notification from KZ about Out of stock
     */
    public function inventoryNotification(Request $request)
    {
        Logger::i(Logger::API_KZ_PRODUCTS)
            ->info(__FUNCTION__.' triggered', [
                'request_all' => $request->all(),
            ]);

        $raw_data = $request->input('data');

        $logMetadata = [
            'raw_data' => $raw_data,
            'requestParams' => $request->all(),
            'requestBody' => $request->getContent()
        ];

        $decrypted = null;
        try {
            $crypt = new Crypt();

            $crypt->setKey(env('KZ_API_SECRET', ''));
            $crypt->setComplexTypes(TRUE);
            $crypt->setData($raw_data);
            $decrypted = $crypt->decrypt();
        }
        catch(Exception $e) {
            Logger::i(Logger::API_KZ_PRODUCTS)
                ->error(__FUNCTION__.' error', $logMetadata);

            \Bugsnag::registerCallback(function ($report) use($logMetadata) {
                $report->setMetaData($logMetadata);
            });
            \Bugsnag::notifyException($e);

            return abort(422, 'Cannot process request');
        }

        Logger::i(Logger::API_KZ_PRODUCTS)
            ->info(__FUNCTION__.' decrypted', [
                'decrypted' => $decrypted
            ]);

        // get models for specified options
            foreach ($decrypted['products'] as $notificationProduct) {
                $models = ProductModel::getForInventoryNotification(
                    $notificationProduct['sku'],
                    $notificationProduct['option_id']
                );

                ProductModel::manageInventoryOutOfStock($models);
            }

        return response()->json(['success' => true]);
    }
}
