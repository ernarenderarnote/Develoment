<?php

namespace App\Console\Commands\Shopify;

use Exception;

use Illuminate\Console\Command;

use App\Components\Shopify;
use App\Models\Store;

class UpdateAllVariantsData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:shopify-update-all-variants-data';

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

        $this->updateAllShopifyVariantsInventoryManagement([
            'inventory_management' => 'shopify',
            'inventory_quantity' => 100000000
        ]);

        $this->line('Finished');
    }

    protected function updateAllShopifyVariantsInventoryManagement($data)
    {
        $stores = Store::findSynced();
        $this->info('Starting shopify variants update data, found '.count($stores).' stores');

        foreach($stores as $storeKey => $store) {
            $this->info('Store #'.$store->id.' ('.($storeKey+1).'/'.count($stores).')');

            $store_domain = $store->shopifyDomain();
            $access_token = $store->access_token;

            $this->info('Found '.count($store->products).' products');

            foreach($store->products as $key => $product) {
                $this->info('Product #'.$product->id.' ('.($key+1).'/'.count($store->products).')');
                $bar = $this->output->createProgressBar(count($product->variants));
                foreach($product->variants as $variant) {

                    if (!$variant->provider_variant_id) continue;

                    try {
                        Shopify::i($store_domain, $access_token)
                            ->updateVariant($variant->provider_variant_id, $data);
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
                    $bar->advance();
                }

                $bar->finish();
                $this->line('');
                $this->line('');
            }

        }
    }
}
