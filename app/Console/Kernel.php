<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\ProductModelTemplateVariantsImport::class,
        Commands\Shopify\ShopifyDebug::class,
        Commands\Shopify\UpdateAllVariantsData::class,
        Commands\Shopify\NotifyShopifyOrderFulfillment::class,
        Commands\Shopify\RegenerateAllWebhooks::class,
        Commands\Shopify\ImportMissingOrders::class,
        Commands\BraintreeGetRefunds::class,
        Commands\BraintreeDebug::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // notify KZ API about new placed orders
        $schedule
            ->command('kz:notify-orders')
            ->evenInMaintenanceMode()
            ->withoutOverlapping()
            ->everyFiveMinutes();

        // refresh product model prices to show Incomplete admin tab
       /*  $schedule
            ->command('kz:refresh-product-model-prices')
            ->evenInMaintenanceMode()
            ->hourly(); */

        // get braintree refunds
        $schedule
            ->command('app:braintree-get-refunds')
            ->evenInMaintenanceMode()
            ->withoutOverlapping()
            ->everyFiveMinutes(); 

        // notify shopify fulfillments
        $schedule
            ->command('app:notify-shopify-order-fulfillment')
            ->evenInMaintenanceMode()
            ->withoutOverlapping()
            ->everyTenMinutes();

        // get shopify missing orders
        $schedule
            ->command('app:shopify-import-missing-orders')
            ->evenInMaintenanceMode()
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/crons/shopify-import-missing-orders.log'))
            ->hourly();

        // Health-check
            // create test job
             $schedule
                ->command('queue:queuecheck')
                ->everyTenMinutes();

            // and then check it's status
            $schedule
                ->command('queue:health-check')
                ->everyMinute(); 
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
