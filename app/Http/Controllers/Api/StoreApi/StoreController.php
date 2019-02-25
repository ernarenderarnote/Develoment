<?php

namespace App\Http\Controllers\Api\StoreApi;

use Illuminate\Http\Request;

use App\Models\Store;
use App\Models\StoreSettings;
use App\Http\Requests\Dashboard\Order\OrderShippingFormRequest;

/**
 * Store
 *
 * @Resource("Store", uri="/store", requestHeaders={
 *      "Authorization": "Bearer Ik6nj6HrKiJwVwgMfGOUPOz5Wa6ZuZns1kRli16sZC4YdigLtjJJlzDKdFZt"
 * })
*/
class StoreController extends StoreApiController
{
    /**
     * Get store settings
     * 
     * @Get("/settings")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request(identifier="/store/settings"),
     *      @Response(200, body={"status":200,"isError":false,"message":null,"data":{"store":{"id":1,"name":"mntz","productsCount":44,"settings":{"auto_orders_confirm":1,"auto_push_products":1,"card_charge_limit_enabled":1,"card_charge_limit_amount":"100.00","card_charge_charges_amount":0}}}}),
     *      @Response(500, body={"isError":true,"message":"This action is unauthorized.","status":500})
     * })
     */
    public function getSettings(Request $request)
    {
        return response()->api([
            'store' => $this->getStore()->transformWithSettings()
        ]);
    }
    
    /**
     * Update store orders settings
     *
     * **Body Attributes:**
     *
     * | name | type |
       | --- | --- |
       |_method | string, required | put | Override HTTP method to use PUT |
       |auto_orders_confirm | boolean, optional|
       |auto_push_products | boolean, optional|
       |card_charge_limit_enabled | boolean, optional|
       |card_charge_limit_amount | decimal, optional|
     * 
     * @Put("/settings/orders")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request(identifier="/store/settings/orders", body={
                "_method": "put",
                "auto_orders_confirm": true,
                "auto_push_products":  false,
                "card_charge_limit_enabled": true,
                "card_charge_limit_amount": 100.00
            }),
     *      @Response(200, body={"status":200,"isError":false,"message":null,"data":{"store":{"id":1,"name":"mntz","productsCount":44,"settings":{"auto_orders_confirm":1,"auto_push_products":1,"card_charge_limit_enabled":1,"card_charge_limit_amount":"100.00","card_charge_charges_amount":0}}}}),
     *      @Response(500, body={"isError":true,"message":"This action is unauthorized.","status":500})
     * })
     */
    public function updateOrdersSettings(Request $request)
    {
        $settings = StoreSettings::findByStoreId($this->getStore()->id);
        $settings->update(
            array_filter(
                $request->only([
                    StoreSettings::SETTING_AUTO_ORDERS_CONFIRM,
                    StoreSettings::SETTING_AUTO_PUSH_PRODUCTS,
                    StoreSettings::SETTING_CARD_CHARGE_LIMIT_ENABLED,
                    StoreSettings::SETTING_CARD_CHARGE_LIMIT_AMOUNT
                ]),
                function($val) {
                    return !($val === '' || is_null($val));
                }
            )
        );
        
        return response()->api([
            'store' => $this->getStore()->transformWithSettings()
        ]);
    }
    
    /**
     * Reset store charges
     *
     * Related to setting "Automatic Order Amount Limit"
     * 
     * @Post("/settings/orders/reset-charges")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request(identifier="/store/settings/orders/reset-charges"),
     *      @Response(200, body={"status":200,"isError":false,"message":null,"data":{"store":{"id":1,"name":"mntz","productsCount":44,"settings":{"auto_orders_confirm":1,"auto_push_products":1,"card_charge_limit_enabled":1,"card_charge_limit_amount":"100.00","card_charge_charges_amount":0}}}}),
     *      @Response(500, body={"isError":true,"message":"This action is unauthorized.","status":500})
     * })
     */
    public function resetCharges(Request $request)
    {
        $store = $this->getStore();
        $store->saveSetting(StoreSettings::SETTING_CARD_CHARGE_CHARGES_AMOUNT, 0);
        $store->save();
        
        return response()->api([
            'store' => $this->getStore()->transformWithSettings()
        ]);
    }
}
