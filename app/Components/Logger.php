<?php
namespace App\Components;

use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;

class Logger
{
    const REQUESTS = 'requests';
    const WEBHOOK_ORDERS = 'webhook/orders';
    const WEBHOOK_PRODUCTS = 'webhook/products';
    const WEBHOOK_APP = 'webhook/app';
    const WEBHOOK_BRAINTREE = 'webhook/braintree';
    const API_KZ_ORDER_FULFILLMENTS = 'api/kz/order-fulfillments';
    const API_KZ_ORDER_FULFILLMENTS_SHOPIFY = 'api/kz/order-fulfillments-shopify';
    const API_KZ_ORDERS = 'api/kz/orders';
    const API_KZ_PRODUCTS = 'api/kz/products';
    const API_SHOPIFY_ORDERS = 'api/shopify/orders';
    const PAYMENTS = 'payments';

    protected static $instance = null;
    protected $loggers = [];

    public static function i($type)
    {
        if (empty(static::$instance)) {
            static::$instance = new static();
        }

        if (empty(static::$instance->loggers[$type])) {
            static::$instance->loggers[$type] = new MonologLogger($type);
            static::$instance->loggers[$type]->pushHandler(
                new StreamHandler(static::path($type), MonologLogger::INFO, null, 0664) // 0664 because group should write too
            );
        }

        return static::$instance->loggers[$type];
    }

    public static function path($type)
    {
        $pathnames = [
            static::WEBHOOK_ORDERS => storage_path('logs/webhooks/orders/'.date('Y').'/'.date('m').'/'.date('d').'.log'),
            static::WEBHOOK_PRODUCTS => storage_path('logs/webhooks/products/'.date('Y').'/'.date('m').'/'.date('d').'.log'),
            static::WEBHOOK_APP => storage_path('logs/webhooks/app/'.date('Y-m-d').'.log'),
            static::WEBHOOK_BRAINTREE => storage_path('logs/webhooks/braintree/'.date('Y-m-d').'.log'),
            static::API_KZ_ORDER_FULFILLMENTS => storage_path('logs/api/kz/order-fulfillments/'.date('Y-m-d').'.log'),
            static::API_KZ_ORDER_FULFILLMENTS_SHOPIFY => storage_path('logs/api/kz/order-fulfillments-shopify/'.date('Y-m-d').'.log'),
            static::API_KZ_ORDERS => storage_path('logs/api/kz/orders/'.date('Y').'/'.date('m').'/'.date('d').'.log'),
            static::API_KZ_PRODUCTS => storage_path('logs/api/kz/products/'.date('Y-m-d').'.log'),
            static::API_SHOPIFY_ORDERS => storage_path('logs/api/shopify/orders/'.date('Y-m-d').'.log'),
            static::PAYMENTS => storage_path('logs/payments/'.date('Y-m-d').'.log'),
            static::REQUESTS => storage_path('logs/requests/'.date('Y-m-d').'.log')
        ];
        return array_get($pathnames, $type);
    }

}
