<?php

namespace App\Console\Commands\Shopify;

use Exception;
use Cache;

use Illuminate\Console\Command;

use App\Components\Shopify;
use App\Models\Store;

class RegenerateAllWebhooks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:shopify-regenerate-all-webhooks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerate all Shopify webhooks for all stores';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->line('--------------------------');

        $stores = Store::findSynced();
        $this->info('Starting '.$this->description.', found '.$stores->count().' synced stores');

        $bar = $this->output->createProgressBar($stores->count());
        foreach($stores as $storeKey => $store) {

            if ($store->isInSync()) {
                $store_domain = $store->shopifyDomain();
                $access_token = $store->access_token;

                try {
                    Shopify::i($store_domain, $access_token)->replaceWebhooks();
                    Cache::forget(Store::class.':'.$store->id.':webhooks_exist');
                    Cache::forget(Store::class.':'.$store->id.':webhooks');
                }
                catch(Exception $e) {
                    if (Shopify::is404Exception($e)) {
                        // product doesn't exist on shopify, do nothing
                    }
                    else if (Shopify::is402Exception($e)) {
                        // store is suspended, do nothing
                    }
                    else {
                        sd($variant);
                        throw $e;
                    }
                }

                sleep(1);
            };

            $bar->advance();
        }

        $bar->finish();
        $this->line('');
        $this->line('Finished');
    }
}
