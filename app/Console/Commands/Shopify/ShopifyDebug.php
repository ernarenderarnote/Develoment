<?php

namespace App\Console\Commands\Shopify;

use Illuminate\Console\Command;

use App\Components\Shopify;
use App\Models\Store;

class ShopifyDebug extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:shopify-debug';

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

        //$this->getOrder('theasherhouse.myshopify.com', '5788774931');
			
		$this->getProductVariantMetafields('kottontees.myshopify.com', '171613683741', '3807690620957');
		//$this->getProduct('kottontees.myshopify.com', '213804613661');
		//$this->getProductMeta('kottontees.myshopify.com', '306369265693');

        $this->line('Finished');
    }

    protected function triggerProductUpdate($shopifyDomain, $product_id)
	{
		$store = Store::findByDomain($shopifyDomain)->first();

        if (!$store) {
			return $this->error("Store doesn't exist");
		}

		$store_domain = $store->shopifyDomain();
        $access_token = $store->access_token;

		$call = Shopify::i($store_domain, $access_token)->api->call([
            'URL' => 'admin/products/'.$product_id.'.json',
            'METHOD' => 'GET'
        ]);

		$this->line('"'.$call->product->tags.'"');

        $call = Shopify::i($store_domain, $access_token)->api->call([
            'URL' => 'admin/products/'.$product_id.'.json',
            'METHOD' => 'PUT',
            'DATA' => [
				'product' => [
					'id' => $product_id,
					'tags' => '_'
				]
			]
        ]);

		$this->line('"'.$call->product->tags.'"');

		$call = Shopify::i($store_domain, $access_token)->api->call([
            'URL' => 'admin/products/'.$product_id.'.json',
            'METHOD' => 'PUT',
            'DATA' => [
				'product' => [
					'id' => $product_id,
					'tags' => ''
				]
			]
        ]);

		$this->line('"'.$call->product->tags.'"');
	}

    protected function getOrder($shopifyDomain, $id)
    {
        $store = Store::findByDomain($shopifyDomain)->first();

        if (!$store) {
			return $this->error("Store doesn't exist");
		}

        $store_domain = $store->shopifyDomain();
        $access_token = $store->access_token;

        $call = Shopify::i($store_domain, $access_token)->api->call([
            'URL' => 'admin/orders/'.$id.'.json',
            'METHOD' => 'GET'
        ]);

        $order = $call->order;

        $data = [];

        $data[] = [
            'id' => $order->id,
            'name' => $order->name,
            'financial_status' => $order->financial_status,
            'fulfillment_status' => $order->fulfillment_status,
            'total_price_usd' => $order->total_price_usd
        ];

        $this->table([
            'id',
            'name',
            'financial_status',
            'fulfillment_status',
            'total_price_usd'
        ], $data);
    }

    protected function getOrders($shopifyDomain)
    {
        $store = Store::findByDomain($shopifyDomain)->first();

        if (!$store) {
			return $this->error("Store doesn't exist");
		}

        $store_domain = $store->shopifyDomain();
        $access_token = $store->access_token;

        $call = Shopify::i($store_domain, $access_token)->getOrders();

        $orders = $call->orders;

        $data = [];

		foreach($orders as $order) {
			$data[] = [
				'id' => $order->id,
				'name' => $order->name,
                'email' => $order->email,
				'financial_status' => $order->financial_status,
				'fulfillment_status' => $order->fulfillment_status,
				'total_price_usd' => $order->total_price_usd
			];
		}

        $this->table([
            'id',
            'name',
            'email',
            'financial_status',
            'fulfillment_status',
            'total_price_usd'
        ], $data);
    }

    protected function getWebhooks($shopifyDomain)
    {
        $store = Store::findByDomain($shopifyDomain)->first();

        if (!$store) {
			return $this->error("Store doesn't exist");
		}

        $store_domain = $store->shopifyDomain();
        $access_token = $store->access_token;

        $call = Shopify::i($store_domain, $access_token)->getWebhooks();

        $webhooks = $call->webhooks;

        $data = [];

		foreach($webhooks as $webhook) {
			$data[] = [
				'id' => $webhook->id,
				'address' => $webhook->address,
				'topic' => $webhook->topic,
				'created_at' => $webhook->created_at,
				'updated_at' => $webhook->updated_at,
				'format' => $webhook->format,
			];
		}

        $this->table([
            'id',
            'address',
            'topic',
            'created_at',
			'updated_at',
            'format'
        ], $data);
    }

    protected function getProductVariantMetafields($shopifyDomain, $product_id, $variant_id)
    {

        $store = Store::findByDomain($shopifyDomain)->first();

        if (!$store) {
			return $this->error("Store doesn't exist");
		}

        $store_domain = $store->shopifyDomain();
        $access_token = $store->access_token;

        $call = Shopify::i($store_domain, $access_token)->api->call([
            'URL' => '/admin/products/'.$product_id.'/variants/'.$variant_id.'/metafields.json',
            'METHOD' => 'GET'
        ]);

		$this->line(print_r($call, true));
    }
	
	protected function getProductVariant($shopifyDomain, $product_id, $variant_id)
    {

        $store = Store::findByDomain($shopifyDomain)->first();

        if (!$store) {
			return $this->error("Store doesn't exist");
		}

        $store_domain = $store->shopifyDomain();
        $access_token = $store->access_token;

        $call = Shopify::i($store_domain, $access_token)->api->call([
            'URL' => '/admin/products/'.$product_id.'/variants/'.$variant_id.'.json',
            'METHOD' => 'GET'
        ]);

		$this->line(print_r($call, true));
    }
	
	protected function getProduct($shopifyDomain, $product_id)
    {

        $store = Store::findByDomain($shopifyDomain)->first();

        if (!$store) {
			return $this->error("Store doesn't exist");
		}

        $store_domain = $store->shopifyDomain();
        $access_token = $store->access_token;

        $call = Shopify::i($store_domain, $access_token)->api->call([
            'URL' => '/admin/products/'.$product_id.'.json',
            'METHOD' => 'GET'
        ]);

		$this->line(print_r($call, true));
    }
	
	protected function getProductMeta($shopifyDomain, $product_id)
    {

        $store = Store::findByDomain($shopifyDomain)->first();

        if (!$store) {
			return $this->error("Store doesn't exist");
		}

        $store_domain = $store->shopifyDomain();
        $access_token = $store->access_token;

        $call = Shopify::i($store_domain, $access_token)->api->call([
            'URL' => '/admin/products/'.$product_id.'/metafields.json',
            'METHOD' => 'GET'
        ]);

		$this->line(print_r($call, true));
    }
	
}
