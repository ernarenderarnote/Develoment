<?php

namespace App\Console\Commands\Shopify;

use Auth;
use Bugsnag;
use DateTime;
use Exception;
use Log;

use Illuminate\Console\Command;

use App\Components\Shopify;
use App\Models\Store;
use App\Models\Order;

class ImportMissingOrders extends Command
{
    protected $signature = 'app:shopify-import-missing-orders
        {--id= : Store id}
        {--created_at_min= : Orders created since relative date, e.g. "-4 hour" or "2017-12-13"}
    ';
    protected $description = 'Import missing orders from Shopify';

    public function handle()
    {
        $id = (int)$this->option('id');
        $created_at_min = $this->option('created_at_min');

        if (!$created_at_min) {
            $created_at_min = '-4 hour';
        }
        else if ($created_at_min && !strtotime($created_at_min)) {
            return $this->error('Relative date "'.$created_at_min.'" cannot be parsed by strtotime()');
        }

        if (!empty($id)) {
            $stores = Store::whichSynced()->where('id', $id)->get();
        }
        else {
            $stores = Store::findSynced();
        }

        $this->info('Starting '.$this->description.', found '.count($stores).' stores');

        foreach($stores as $storeKey => $store) {
            $this->info('Store #'.$store->id.' ('.($storeKey+1).'/'.count($stores).')');

            if (!$store->user) {
                Bugsnag::notifyException(new Exception("User does not exist for store {$store->id}"));
                continue;
            }

            // we need to auth user to get his price modifiers
            Auth::onceUsingId($store->user->id);

            $store_domain = $store->shopifyDomain();
            $access_token = $store->access_token;

            $shopifyCall = null;
            try {
                if ($id) {
                    $shopifyCall = Shopify::i($store_domain, $access_token)
                        ->searchOrdersAfter(new DateTime($created_at_min));
                }
                else {
                    $shopifyCall = Shopify::i($store_domain, $access_token)
                        ->searchOrdersAfter(new DateTime('-4 hour'));
                }
            }
            catch(Exception $e) {
                if (Shopify::is404Exception($e)) {
                    // store doesn't exist on shopify, do nothing
                    $this->info("Store doesn't exist, do nothing");
                    continue;
                }
                else if (Shopify::is402Exception($e)) {
                    // store is suspended, do nothing
                    $this->info('Store is suspended, do nothing');
                    continue;
                }
                else {
                    Bugsnag::registerCallback(function ($report) use($store) {
                        $report->setMetaData([
                            'store_id' => $store->id
                        ]);
                    });
                    Bugsnag::notifyException($e);
                    Log::error($e);
                }
            }

            if ($shopifyCall) {
                $this->info('Found '.count($shopifyCall->orders).' orders');
                $bar = $this->output->createProgressBar(count($shopifyCall->orders));
                foreach($shopifyCall->orders as $shopifyOrder) {

                    Order::pullFromShopifyJson($store, $shopifyOrder);
                    sleep(1);

                    $bar->advance();
                }

                $bar->finish();
                $this->line('');
                $this->line('');
            }

            sleep(1);
        }

        $this->line('Finished');
    }
}
