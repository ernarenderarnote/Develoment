<?php

namespace App\Http\Controllers\Api\StoreApi;

use DB;
use Input;
use DingoRoute;
use Cache;
use Carbon\Carbon;
use Exception;

use Request as RequestFacade;
use Dingo\Api\Http\Request as DingoRequest;
use Illuminate\Http\Request;

use App\Components\Money;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\Payment;
use App\Transformers\Order\OrderBriefTransformer;
use App\Http\Requests\Dashboard\Order\OrderShippingFormRequest;
use App\Http\Requests\Dashboard\Order\OrderRefundFormRequest;

/**
 * Orders
 *
 * @Resource("Orders", uri="/orders", requestHeaders={
 *      "Authorization": "Bearer Ik6nj6HrKiJwVwgMfGOUPOz5Wa6ZuZns1kRli16sZC4YdigLtjJJlzDKdFZt"
 * })
*/
class OrdersController extends StoreApiController
{
    /**
     * Orders list
     *
     * Get current store's orders list. Could be used with name filter.
     *
     * @Get("/?page={page}&search={search}&status={status}")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request(identifier="/orders/?page=1&search=John Doe&status=draft"),
     *      @Response(200, body={"status":200,"isError":false,"message":null,"data":{"orders":{"data":{{"id":12,"order_number":1054,"status":"placed","statusName":"Placed","payment_status":"paid","paymentStatusName":"Paid","fulfillment_status":"unfulfilled","fulfillmentStatusName":"Unfulfilled","refund_status":null,"refund_status_comment":null,"refundStatusName":null,"action_required":null,"actionRequiredDescription":null,"total":{"amount":"4690","currency":"USD"},"subtotal":{"amount":"1590","currency":"USD"},"profit":{"amount":"3000","currency":"USD"},"shipping_method":null,"shippingPrice":{"amount":"3100","currency":"USD"},"shipping_retail_costs":"1.00","customer_paid_price":{"amount":"7690","currency":"USD"},"customerPaidPrice":{"amount":"7690","currency":"USD"},"customerShippingRetailCostsPrice":{"amount":"100","currency":"USD"},"customer_meta":{"id":3210186823,"email":"edward.aws3+test@gmail.com","accepts_marketing":false,"created_at":"2016-05-03T08:37:37-04:00","updated_at":"2016-11-18T19:57:37-05:00","first_name":"Test Name","last_name":"Name","orders_count":0,"state":"disabled","total_spent":"0.00","last_order_id":null,"note":null,"verified_email":true,"multipass_identifier":null,"tax_exempt":false,"tags":"","last_order_name":null,"default_address":{"id":4941309447,"first_name":"Test Name","last_name":"Name","company":null,"address1":"78760 Schroeder Parkways","address2":"123123","city":"West Marianne","province":"Alabama","country":"United States","zip":"35022","phone":null,"name":"Test Name Name","province_code":"AL","country_code":"US","country_name":"United States","default":true}},"shipping_meta":{"first_name":"Aaliyah","last_name":"Jenkins","company":"Dach - Muller","address1":"5982 Darian Island","address2":"","city":"Gustaveland","province":"SC","country":"United States","country_code":"US","zip":"75531","phone":"253-303-0743"},"billing_meta":{"first_name":"","last_name":"","company":"","address1":"","address2":"","city":"","province":"","country":"","country_code":"","zip":"","phone":""},"createdAt":"2016-11-19T00:57:37+00:00","updatedAt":"2016-12-26T17:46:20+00:00","providerUrl":"https:\/\/mntz-2.myshopify.com\/admin\/orders\/4759648970","tracking_number":"TN-123456789","policy":{"allowed":{"show":true,"edit":true,"cancel":false,"refund":true,"delete":true,"pay":false},"denied":{"show":true,"edit":true,"cancel":false,"refund":true,"delete":true,"pay":false}}}},"meta":{"pagination":{"total":12,"count":1,"per_page":1,"current_page":1,"total_pages":12,"links":{"next":"http:\/\/monetize-social.dev\/api\/store-api\/v1\/orders?page=2"}}}},"stats":{"ordersToday":0,"ordersSumToday":{"amount":"0","currency":"USD"},"ordersLast7Days":0,"ordersSumLast7Days":{"amount":"0","currency":"USD"},"ordersLast28Days":0,"ordersSumLast28Days":{"amount":"0","currency":"USD"},"ordersProfitLast28Days":{"amount":"0","currency":"USD"}}}})
     * })
     * @Parameters({
     *      @Parameter("page", type="integer", required=false, description="Pagination page", default=1),
     *      @Parameter("search", type="string", required=false, description="Search order number + shipping info"),
     *      @Parameter("status", type="string", required=false, description="Product status filter", default="synced", members={
     *          @Member(value="draft", description="New orders from provider"),
     *          @Member(value="placed", description="Placed (paid) orders"),
     *          @Member(value="completed", description="Completed orders"),
     *          @Member(value="cancelled", description="Cancelled orders")
     *      })
     * })
     */
    public function index(Request $request)
    {
        $search = filter_var($request->get('search'), FILTER_SANITIZE_STRING);
        $status = filter_var($request->get('status'), FILTER_SANITIZE_STRING);

        $ordersQuery = auth()->user()->orders()
            ->whereHas('store', function($query) {
                $query->whereNull('deleted_at');
            })
            ->where('store_id', $this->getStore()->id)
            ->orderBy('created_at', 'desc')
            ->search($search);

        if ($status) {
            $ordersQuery->where('status', $status);
        }

        $orders = $ordersQuery->paginate(10);

        $storeIds = auth()->user()->stores->pluck('id')->toArray();

        return response()->api([
            'orders' => $this->paginator($orders, new OrderBriefTransformer),
            'stats' => [
                'ordersToday' => Order::countOrdersStartingFromTime($storeIds, strtotime('-1 day')),
                'ordersSumToday' => Order::getOrdersCustomerPaidStartingFromTime($storeIds, strtotime('-1 day')),

                'ordersLast7Days' => Order::countOrdersStartingFromTime($storeIds, strtotime('-7 day')),
                'ordersSumLast7Days' => Order::getOrdersCustomerPaidStartingFromTime($storeIds, strtotime('-7 day')),

                'ordersLast28Days' => Order::countOrdersStartingFromTime($storeIds, strtotime('-28 day')),
                'ordersSumLast28Days' => Order::getOrdersCustomerPaidStartingFromTime($storeIds, strtotime('-28 day')),

                'ordersProfitLast28Days' => Order::getOrdersProfitStartingFromTime($storeIds, strtotime('-28 day')),
            ]
        ]);
    }

    /**
     * Create direct order
     *
     * @Post("/")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request({}),
     *      @Response(200, body={"status":200,"isError":false,"message":null,"data":{"order":{"id":19,"order_number":"direct_BxXKGd","status":"draft","statusName":"Draft","payment_status":"pending","paymentStatusName":"Pending","fulfillment_status":"unfulfilled","fulfillmentStatusName":"Unfulfilled","refund_status":null,"refund_status_comment":null,"refundStatusName":null,"action_required":null,"actionRequiredDescription":null,"total":{"amount":"0","currency":"USD"},"subtotal":{"amount":"0","currency":"USD"},"profit":{"amount":"0","currency":"USD"},"shipping_method":null,"shippingPrice":{"amount":"0","currency":"USD"},"shipping_retail_costs":null,"customerShippingRetailCostsPrice":{"amount":"0","currency":"USD"},"customer_paid_price":{"amount":"0","currency":"USD"},"customerPaidPrice":{"amount":"0","currency":"USD"},"customer_meta":null,"shipping_meta":{"first_name":"","last_name":"","company":"","address1":"","address2":"","city":"","province":"","country":"","country_code":"","zip":"","phone":""},"billing_meta":{"first_name":"","last_name":"","company":"","address1":"","address2":"","city":"","province":"","country":"","country_code":"","zip":"","phone":""},"createdAt":"2017-01-24T23:58:25+00:00","updatedAt":"2017-01-24T23:58:25+00:00","providerUrl":"https:\/\/mntz-2.myshopify.com\/admin\/orders\/","isPaid":false,"tracking_number":"TN-123456789","policy":{"allowed":{"add":true,"show":true,"edit":true,"edit_variants":true,"cancel":true,"refund":false,"delete":true,"pay":false},"denied":{"add":false,"show":false,"edit":false,"edit_variants":false,"cancel":false,"refund":true,"delete":false,"pay":true}},"variants":{},"store":{"id":1,"name":"mntz","productsCount":44}}}})
     * })
     */
    public function store(Request $request)
    {
        $order = new Order();
        $order->store_id = $this->getStore()->id;
        $this->authorize('add', $order);

        // get id
        $order->save();

        // get hashid id
        $order->order_number = 'direct_'.$order->id();
        $order->save();

        return response()->api([
            'order' => $order->transformFull()
        ]);
    }

    /**
     * Get order
     *
     * @Get("/{order}")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request(identifier="/orders/12"),
     *      @Response(200, body={"status":200,"isError":false,"message":null,"data":{"order":{"id":12,"order_number":1054,"status":"placed","statusName":"Placed","payment_status":"paid","paymentStatusName":"Paid","fulfillment_status":"unfulfilled","fulfillmentStatusName":"Unfulfilled","refund_status":null,"refund_status_comment":null,"refundStatusName":null,"action_required":null,"actionRequiredDescription":null,"total":{"amount":"4690","currency":"USD"},"subtotal":{"amount":"1590","currency":"USD"},"profit":{"amount":"3000","currency":"USD"},"shipping_method":null,"shippingPrice":{"amount":"3100","currency":"USD"},"shipping_retail_costs":"1.00","customer_paid_price":{"amount":"7690","currency":"USD"},"customerPaidPrice":{"amount":"7690","currency":"USD"},"customerShippingRetailCostsPrice":{"amount":"100","currency":"USD"},"customer_meta":{"id":3210186823,"email":"edward.aws3+test@gmail.com","accepts_marketing":false,"created_at":"2016-05-03T08:37:37-04:00","updated_at":"2016-11-18T19:57:37-05:00","first_name":"Test Name","last_name":"Name","orders_count":0,"state":"disabled","total_spent":"0.00","last_order_id":null,"note":null,"verified_email":true,"multipass_identifier":null,"tax_exempt":false,"tags":"","last_order_name":null,"default_address":{"id":4941309447,"first_name":"Test Name","last_name":"Name","company":null,"address1":"78760 Schroeder Parkways","address2":"123123","city":"West Marianne","province":"Alabama","country":"United States","zip":"35022","phone":null,"name":"Test Name Name","province_code":"AL","country_code":"US","country_name":"United States","default":true}},"shipping_meta":{"first_name":"Aaliyah","last_name":"Jenkins","company":"Dach - Muller","address1":"5982 Darian Island","address2":"","city":"Gustaveland","province":"SC","country":"United States","country_code":"US","zip":"75531","phone":"253-303-0743"},"billing_meta":{"first_name":"","last_name":"","company":"","address1":"","address2":"","city":"","province":"","country":"","country_code":"","zip":"","phone":""},"createdAt":"2016-11-19T00:57:37+00:00","updatedAt":"2016-12-26T17:46:20+00:00","providerUrl":"https:\/\/mntz-2.myshopify.com\/admin\/orders\/4759648970","tracking_number":"TN-123456789","policy":{"allowed":{"show":true,"edit":true,"cancel":false,"refund":true,"delete":true,"pay":false},"denied":{"show":true,"edit":true,"cancel":false,"refund":true,"delete":true,"pay":false}},"variants":{{"id":91,"name":"Vapor ladies v-neck dress Girl - L White","status":"active","quantity":2,"printPriceMoney":{"amount":"0","currency":"USD"},"customerPaidPriceMoney":{"amount":"1000","currency":"USD"},"model":{"id":2282,"price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"frontPrice":"0.00","frontPriceMoney":{"amount":"0","currency":"USD"},"backPrice":"0.00","backPriceMoney":{"amount":"0","currency":"USD"},"bothSidesPrice":"0.00","bothSidesPriceMoney":{"amount":"0","currency":"USD"},"options":{{"id":5,"name":"L","value":"l","kz_option_id":4,"attribute":{"id":1,"name":"Size","value":"size"}},{"id":7,"name":"White","value":"#ffffff","kz_option_id":28,"attribute":{"id":2,"name":"Color","value":"color"}}},"template":{"id":39,"name":"Vapor ladies v-neck dress Girl","price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"product_title":"Vapor ladies v-neck dress Girl","product_description":"Vapor ladies v-neck dress Girl","preview":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/358\/thumb\/ladies_v_front_bro_science_empire_lifts_back_web.jpg","image":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/359\/original\/bella_6004_women_crew_front.png","imageBack":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/363\/original\/bella_6004_women_crew_front.png","backPrintCanBeAddedOnItsOwn":true,"catalogAttributes":{{"id":1,"value":"size","name":"Size","pivot":{"category_id":3,"attribute_id":1}},{"id":2,"value":"color","name":"Color","pivot":{"category_id":3,"attribute_id":2}}},"category":{"id":3,"name":"Wild Tees","slug":"wild-tee","preview":null,"children":{},"templates":{}}}},"mockups":{{"id":408,"type":"print_file_mockup","typeName":"Print file mockup","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/408\/medium\/variant-mockup-91.jpg","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/408\/original\/variant-mockup-91.jpg"}},"product":{"id":67,"name":"Vapor ladies v-neck dress Girl","status":"active"}},{"id":90,"name":"Vapor ladies v-neck dress Girl - M White","status":"active","quantity":1,"printPriceMoney":{"amount":"0","currency":"USD"},"customerPaidPriceMoney":{"amount":"1000","currency":"USD"},"model":{"id":2281,"price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"frontPrice":"0.00","frontPriceMoney":{"amount":"0","currency":"USD"},"backPrice":"0.00","backPriceMoney":{"amount":"0","currency":"USD"},"bothSidesPrice":"0.00","bothSidesPriceMoney":{"amount":"0","currency":"USD"},"options":{{"id":4,"name":"M","value":"m","kz_option_id":3,"attribute":{"id":1,"name":"Size","value":"size"}},{"id":7,"name":"White","value":"#ffffff","kz_option_id":28,"attribute":{"id":2,"name":"Color","value":"color"}}},"template":{"id":39,"name":"Vapor ladies v-neck dress Girl","price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"product_title":"Vapor ladies v-neck dress Girl","product_description":"Vapor ladies v-neck dress Girl","preview":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/358\/thumb\/ladies_v_front_bro_science_empire_lifts_back_web.jpg","image":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/359\/original\/bella_6004_women_crew_front.png","imageBack":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/363\/original\/bella_6004_women_crew_front.png","backPrintCanBeAddedOnItsOwn":true,"catalogAttributes":{{"id":1,"value":"size","name":"Size","pivot":{"category_id":3,"attribute_id":1}},{"id":2,"value":"color","name":"Color","pivot":{"category_id":3,"attribute_id":2}}},"category":{"id":3,"name":"Wild Tees","slug":"wild-tee","preview":null,"children":{},"templates":{}}}},"mockups":{{"id":407,"type":"print_file_mockup","typeName":"Print file mockup","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/407\/medium\/variant-mockup-90.jpg","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/407\/original\/variant-mockup-90.jpg"}},"product":{"id":67,"name":"Vapor ladies v-neck dress Girl","status":"active"}},{"id":111,"name":"Crop Tank Top Girl - M White","status":"active","quantity":3,"printPriceMoney":{"amount":"530","currency":"USD"},"customerPaidPriceMoney":{"amount":"1530","currency":"USD"},"model":{"id":470,"price":"5.30","priceMoney":{"amount":"530","currency":"USD"},"frontPrice":"5.30","frontPriceMoney":{"amount":"530","currency":"USD"},"backPrice":"0.00","backPriceMoney":{"amount":"0","currency":"USD"},"bothSidesPrice":"5.30","bothSidesPriceMoney":{"amount":"530","currency":"USD"},"options":{{"id":4,"name":"M","value":"m","kz_option_id":3,"attribute":{"id":1,"name":"Size","value":"size"}},{"id":7,"name":"White","value":"#ffffff","kz_option_id":28,"attribute":{"id":2,"name":"Color","value":"color"}}},"template":{"id":13,"name":"Crop Tank Top Girl","price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"product_title":"Crop Tank Top Girl","product_description":"Crop Tank Top Girl","preview":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/397\/thumb\/aa_8384_crop_top_front.png","image":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/289\/original\/aa_8384_crop_top_front.png","imageBack":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/290\/original\/aa_8384_crop_top_back.png","backPrintCanBeAddedOnItsOwn":false,"catalogAttributes":{{"id":1,"value":"size","name":"Size","pivot":{"category_id":2,"attribute_id":1}},{"id":2,"value":"color","name":"Color","pivot":{"category_id":2,"attribute_id":2}}},"category":{"id":2,"name":"Reg Tees","slug":"reg-tee","preview":null,"children":{},"templates":{}}}},"mockups":{{"id":431,"type":"print_file_mockup","typeName":"Print file mockup","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/431\/medium\/variant-mockup-111.jpg","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/431\/original\/variant-mockup-111.jpg"}},"product":{"id":83,"name":"Crop Tank Top Girl","status":"active"}}}}}}),
     *      @Response(500, body={"isError":true,"message":"This action is unauthorized.","status":500})
     * })
     * @Parameters({
     *      @Parameter("order", type="string", required=true, description="Order ID")
     * })
     */
    public function show(Request $request, Order $order)
    {
        $this->authorize('show', $order);

        return response()->api([
            'order' => $order->transformFull()
        ]);
    }

    /**
     * Update order's shipping info
     *
     * **Body Attributes:**
     *
     * | name | type |
       | --- | --- |
       |_method | string, required | put | Override HTTP method to use PUT |
       |first_name | string, required, max:255|
       |last_name | string, required, max:255|
       |address1 | string, required, max:255|
       |address2 | string, max:255|
       |city | string, required, max:255|
       |province | string, required, max:255|
       |country_code | string, required, in:ISO-3166|
       |zip | string, required, max:25|
       |company | string, max:255|
       |phone | string, max:255|
       |shipping_method | string, required, max:255, in:first_class,priority_mail|
     *
     * @Put("/{order}/shipping")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request(identifier="/orders/12/shipping", body={
                "_method": "put",
                "first_name": "Aaliyah",
                "last_name": "Jenkins",
                "address1": "5982 Darian Island",
                "address2": "",
                "city": "Gustaveland",
                "province": "SC",
                "country_code": "US",
                "zip": "75531",
                "company": "Dach - Muller",
                "phone": "253-303-0743",
                "shipping_method": "first_class"
            }),
     *      @Response(200, body={"status":200,"isError":false,"message":null,"data":{"order":{"id":12,"order_number":1054,"status":"placed","statusName":"Placed","payment_status":"paid","paymentStatusName":"Paid","fulfillment_status":"unfulfilled","fulfillmentStatusName":"Unfulfilled","refund_status":null,"refund_status_comment":null,"refundStatusName":null,"action_required":null,"actionRequiredDescription":null,"total":{"amount":"4690","currency":"USD"},"subtotal":{"amount":"1590","currency":"USD"},"profit":{"amount":"3000","currency":"USD"},"shipping_method":null,"shippingPrice":{"amount":"3100","currency":"USD"},"shipping_retail_costs":"1.00","customer_paid_price":{"amount":"7690","currency":"USD"},"customerPaidPrice":{"amount":"7690","currency":"USD"},"customerShippingRetailCostsPrice":{"amount":"100","currency":"USD"},"customer_meta":{"id":3210186823,"email":"edward.aws3+test@gmail.com","accepts_marketing":false,"created_at":"2016-05-03T08:37:37-04:00","updated_at":"2016-11-18T19:57:37-05:00","first_name":"Test Name","last_name":"Name","orders_count":0,"state":"disabled","total_spent":"0.00","last_order_id":null,"note":null,"verified_email":true,"multipass_identifier":null,"tax_exempt":false,"tags":"","last_order_name":null,"default_address":{"id":4941309447,"first_name":"Test Name","last_name":"Name","company":null,"address1":"78760 Schroeder Parkways","address2":"123123","city":"West Marianne","province":"Alabama","country":"United States","zip":"35022","phone":null,"name":"Test Name Name","province_code":"AL","country_code":"US","country_name":"United States","default":true}},"shipping_meta":{"first_name":"Aaliyah","last_name":"Jenkins","company":"Dach - Muller","address1":"5982 Darian Island","address2":"","city":"Gustaveland","province":"SC","country":"United States","country_code":"US","zip":"75531","phone":"253-303-0743"},"billing_meta":{"first_name":"","last_name":"","company":"","address1":"","address2":"","city":"","province":"","country":"","country_code":"","zip":"","phone":""},"createdAt":"2016-11-19T00:57:37+00:00","updatedAt":"2016-12-26T17:46:20+00:00","providerUrl":"https:\/\/mntz-2.myshopify.com\/admin\/orders\/4759648970","tracking_number":"TN-123456789","policy":{"allowed":{"show":true,"edit":true,"cancel":false,"refund":true,"delete":true,"pay":false},"denied":{"show":true,"edit":true,"cancel":false,"refund":true,"delete":true,"pay":false}},"variants":{{"id":91,"name":"Vapor ladies v-neck dress Girl - L White","status":"active","quantity":2,"printPriceMoney":{"amount":"0","currency":"USD"},"customerPaidPriceMoney":{"amount":"1000","currency":"USD"},"model":{"id":2282,"price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"frontPrice":"0.00","frontPriceMoney":{"amount":"0","currency":"USD"},"backPrice":"0.00","backPriceMoney":{"amount":"0","currency":"USD"},"bothSidesPrice":"0.00","bothSidesPriceMoney":{"amount":"0","currency":"USD"},"options":{{"id":5,"name":"L","value":"l","kz_option_id":4,"attribute":{"id":1,"name":"Size","value":"size"}},{"id":7,"name":"White","value":"#ffffff","kz_option_id":28,"attribute":{"id":2,"name":"Color","value":"color"}}},"template":{"id":39,"name":"Vapor ladies v-neck dress Girl","price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"product_title":"Vapor ladies v-neck dress Girl","product_description":"Vapor ladies v-neck dress Girl","preview":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/358\/thumb\/ladies_v_front_bro_science_empire_lifts_back_web.jpg","image":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/359\/original\/bella_6004_women_crew_front.png","imageBack":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/363\/original\/bella_6004_women_crew_front.png","backPrintCanBeAddedOnItsOwn":true,"catalogAttributes":{{"id":1,"value":"size","name":"Size","pivot":{"category_id":3,"attribute_id":1}},{"id":2,"value":"color","name":"Color","pivot":{"category_id":3,"attribute_id":2}}},"category":{"id":3,"name":"Wild Tees","slug":"wild-tee","preview":null,"children":{},"templates":{}}}},"mockups":{{"id":408,"type":"print_file_mockup","typeName":"Print file mockup","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/408\/medium\/variant-mockup-91.jpg","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/408\/original\/variant-mockup-91.jpg"}},"product":{"id":67,"name":"Vapor ladies v-neck dress Girl","status":"active"}},{"id":90,"name":"Vapor ladies v-neck dress Girl - M White","status":"active","quantity":1,"printPriceMoney":{"amount":"0","currency":"USD"},"customerPaidPriceMoney":{"amount":"1000","currency":"USD"},"model":{"id":2281,"price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"frontPrice":"0.00","frontPriceMoney":{"amount":"0","currency":"USD"},"backPrice":"0.00","backPriceMoney":{"amount":"0","currency":"USD"},"bothSidesPrice":"0.00","bothSidesPriceMoney":{"amount":"0","currency":"USD"},"options":{{"id":4,"name":"M","value":"m","kz_option_id":3,"attribute":{"id":1,"name":"Size","value":"size"}},{"id":7,"name":"White","value":"#ffffff","kz_option_id":28,"attribute":{"id":2,"name":"Color","value":"color"}}},"template":{"id":39,"name":"Vapor ladies v-neck dress Girl","price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"product_title":"Vapor ladies v-neck dress Girl","product_description":"Vapor ladies v-neck dress Girl","preview":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/358\/thumb\/ladies_v_front_bro_science_empire_lifts_back_web.jpg","image":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/359\/original\/bella_6004_women_crew_front.png","imageBack":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/363\/original\/bella_6004_women_crew_front.png","backPrintCanBeAddedOnItsOwn":true,"catalogAttributes":{{"id":1,"value":"size","name":"Size","pivot":{"category_id":3,"attribute_id":1}},{"id":2,"value":"color","name":"Color","pivot":{"category_id":3,"attribute_id":2}}},"category":{"id":3,"name":"Wild Tees","slug":"wild-tee","preview":null,"children":{},"templates":{}}}},"mockups":{{"id":407,"type":"print_file_mockup","typeName":"Print file mockup","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/407\/medium\/variant-mockup-90.jpg","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/407\/original\/variant-mockup-90.jpg"}},"product":{"id":67,"name":"Vapor ladies v-neck dress Girl","status":"active"}},{"id":111,"name":"Crop Tank Top Girl - M White","status":"active","quantity":3,"printPriceMoney":{"amount":"530","currency":"USD"},"customerPaidPriceMoney":{"amount":"1530","currency":"USD"},"model":{"id":470,"price":"5.30","priceMoney":{"amount":"530","currency":"USD"},"frontPrice":"5.30","frontPriceMoney":{"amount":"530","currency":"USD"},"backPrice":"0.00","backPriceMoney":{"amount":"0","currency":"USD"},"bothSidesPrice":"5.30","bothSidesPriceMoney":{"amount":"530","currency":"USD"},"options":{{"id":4,"name":"M","value":"m","kz_option_id":3,"attribute":{"id":1,"name":"Size","value":"size"}},{"id":7,"name":"White","value":"#ffffff","kz_option_id":28,"attribute":{"id":2,"name":"Color","value":"color"}}},"template":{"id":13,"name":"Crop Tank Top Girl","price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"product_title":"Crop Tank Top Girl","product_description":"Crop Tank Top Girl","preview":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/397\/thumb\/aa_8384_crop_top_front.png","image":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/289\/original\/aa_8384_crop_top_front.png","imageBack":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/290\/original\/aa_8384_crop_top_back.png","backPrintCanBeAddedOnItsOwn":false,"catalogAttributes":{{"id":1,"value":"size","name":"Size","pivot":{"category_id":2,"attribute_id":1}},{"id":2,"value":"color","name":"Color","pivot":{"category_id":2,"attribute_id":2}}},"category":{"id":2,"name":"Reg Tees","slug":"reg-tee","preview":null,"children":{},"templates":{}}}},"mockups":{{"id":431,"type":"print_file_mockup","typeName":"Print file mockup","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/431\/medium\/variant-mockup-111.jpg","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/431\/original\/variant-mockup-111.jpg"}},"product":{"id":83,"name":"Crop Tank Top Girl","status":"active"}}}}}}),
     *      @Response(422, body={"status":422,"isError":true,"message":"422 Unprocessable Entity","validationErrors":{"first_name":{"The first name field is required."},"last_name":{"The last name field is required."},"address1":{"The address1 field is required."},"city":{"The city field is required."},"province":{"The province field is required."},"country_code":{"The country code field is required."},"zip":{"The zip field is required."}},"data":{}}),
     *      @Response(500, body={"isError":true,"message":"This action is unauthorized.","status":500})
     * })
     * @Parameters({
     *      @Parameter("order", type="string", required=true, description="Order ID")
     * })
     */
    public function saveShipping(OrderShippingFormRequest $request, Order $order)
    {
        $this->authorize('edit_shipping_info', $order);

        $order->shipping_meta = $request->all();
        $order->shipping_method = $request->get('shipping_method');
        $order->save();

        $order->resolveActionRequired(
            Order::ACTION_REQUIRED_SHIPPING_METHOD
        );

        $order->resolveActionRequired(
            Order::ACTION_REQUIRED_AUTO_ORDER_AMOUNT_REACHED
        );

        return response()->api([
            'order' => $order->transformFull()
        ]);
    }

    /**
     * Pay for order
     *
     * Order status will be changed to placed
     *
     * @Post("/{order}/pay")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request(identifier="/orders/12/pay"),
     *      @Response(200, body={"status":200,"isError":false,"message":null,"data":{"order":{"id":12,"order_number":1054,"status":"placed","statusName":"Placed","payment_status":"paid","paymentStatusName":"Paid","fulfillment_status":"unfulfilled","fulfillmentStatusName":"Unfulfilled","refund_status":null,"refund_status_comment":null,"refundStatusName":null,"action_required":null,"actionRequiredDescription":null,"total":{"amount":"4690","currency":"USD"},"subtotal":{"amount":"1590","currency":"USD"},"profit":{"amount":"3000","currency":"USD"},"shipping_method":null,"shippingPrice":{"amount":"3100","currency":"USD"},"shipping_retail_costs":"1.00","customer_paid_price":{"amount":"7690","currency":"USD"},"customerPaidPrice":{"amount":"7690","currency":"USD"},"customerShippingRetailCostsPrice":{"amount":"100","currency":"USD"},"customer_meta":{"id":3210186823,"email":"edward.aws3+test@gmail.com","accepts_marketing":false,"created_at":"2016-05-03T08:37:37-04:00","updated_at":"2016-11-18T19:57:37-05:00","first_name":"Test Name","last_name":"Name","orders_count":0,"state":"disabled","total_spent":"0.00","last_order_id":null,"note":null,"verified_email":true,"multipass_identifier":null,"tax_exempt":false,"tags":"","last_order_name":null,"default_address":{"id":4941309447,"first_name":"Test Name","last_name":"Name","company":null,"address1":"78760 Schroeder Parkways","address2":"123123","city":"West Marianne","province":"Alabama","country":"United States","zip":"35022","phone":null,"name":"Test Name Name","province_code":"AL","country_code":"US","country_name":"United States","default":true}},"shipping_meta":{"first_name":"Aaliyah","last_name":"Jenkins","company":"Dach - Muller","address1":"5982 Darian Island","address2":"","city":"Gustaveland","province":"SC","country":"United States","country_code":"US","zip":"75531","phone":"253-303-0743"},"billing_meta":{"first_name":"","last_name":"","company":"","address1":"","address2":"","city":"","province":"","country":"","country_code":"","zip":"","phone":""},"createdAt":"2016-11-19T00:57:37+00:00","updatedAt":"2016-12-26T17:46:20+00:00","providerUrl":"https:\/\/mntz-2.myshopify.com\/admin\/orders\/4759648970","tracking_number":"TN-123456789","policy":{"allowed":{"show":true,"edit":true,"cancel":false,"refund":true,"delete":true,"pay":false},"denied":{"show":true,"edit":true,"cancel":false,"refund":true,"delete":true,"pay":false}},"variants":{{"id":91,"name":"Vapor ladies v-neck dress Girl - L White","status":"active","quantity":2,"printPriceMoney":{"amount":"0","currency":"USD"},"customerPaidPriceMoney":{"amount":"1000","currency":"USD"},"model":{"id":2282,"price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"frontPrice":"0.00","frontPriceMoney":{"amount":"0","currency":"USD"},"backPrice":"0.00","backPriceMoney":{"amount":"0","currency":"USD"},"bothSidesPrice":"0.00","bothSidesPriceMoney":{"amount":"0","currency":"USD"},"options":{{"id":5,"name":"L","value":"l","kz_option_id":4,"attribute":{"id":1,"name":"Size","value":"size"}},{"id":7,"name":"White","value":"#ffffff","kz_option_id":28,"attribute":{"id":2,"name":"Color","value":"color"}}},"template":{"id":39,"name":"Vapor ladies v-neck dress Girl","price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"product_title":"Vapor ladies v-neck dress Girl","product_description":"Vapor ladies v-neck dress Girl","preview":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/358\/thumb\/ladies_v_front_bro_science_empire_lifts_back_web.jpg","image":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/359\/original\/bella_6004_women_crew_front.png","imageBack":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/363\/original\/bella_6004_women_crew_front.png","backPrintCanBeAddedOnItsOwn":true,"catalogAttributes":{{"id":1,"value":"size","name":"Size","pivot":{"category_id":3,"attribute_id":1}},{"id":2,"value":"color","name":"Color","pivot":{"category_id":3,"attribute_id":2}}},"category":{"id":3,"name":"Wild Tees","slug":"wild-tee","preview":null,"children":{},"templates":{}}}},"mockups":{{"id":408,"type":"print_file_mockup","typeName":"Print file mockup","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/408\/medium\/variant-mockup-91.jpg","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/408\/original\/variant-mockup-91.jpg"}},"product":{"id":67,"name":"Vapor ladies v-neck dress Girl","status":"active"}},{"id":90,"name":"Vapor ladies v-neck dress Girl - M White","status":"active","quantity":1,"printPriceMoney":{"amount":"0","currency":"USD"},"customerPaidPriceMoney":{"amount":"1000","currency":"USD"},"model":{"id":2281,"price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"frontPrice":"0.00","frontPriceMoney":{"amount":"0","currency":"USD"},"backPrice":"0.00","backPriceMoney":{"amount":"0","currency":"USD"},"bothSidesPrice":"0.00","bothSidesPriceMoney":{"amount":"0","currency":"USD"},"options":{{"id":4,"name":"M","value":"m","kz_option_id":3,"attribute":{"id":1,"name":"Size","value":"size"}},{"id":7,"name":"White","value":"#ffffff","kz_option_id":28,"attribute":{"id":2,"name":"Color","value":"color"}}},"template":{"id":39,"name":"Vapor ladies v-neck dress Girl","price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"product_title":"Vapor ladies v-neck dress Girl","product_description":"Vapor ladies v-neck dress Girl","preview":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/358\/thumb\/ladies_v_front_bro_science_empire_lifts_back_web.jpg","image":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/359\/original\/bella_6004_women_crew_front.png","imageBack":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/363\/original\/bella_6004_women_crew_front.png","backPrintCanBeAddedOnItsOwn":true,"catalogAttributes":{{"id":1,"value":"size","name":"Size","pivot":{"category_id":3,"attribute_id":1}},{"id":2,"value":"color","name":"Color","pivot":{"category_id":3,"attribute_id":2}}},"category":{"id":3,"name":"Wild Tees","slug":"wild-tee","preview":null,"children":{},"templates":{}}}},"mockups":{{"id":407,"type":"print_file_mockup","typeName":"Print file mockup","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/407\/medium\/variant-mockup-90.jpg","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/407\/original\/variant-mockup-90.jpg"}},"product":{"id":67,"name":"Vapor ladies v-neck dress Girl","status":"active"}},{"id":111,"name":"Crop Tank Top Girl - M White","status":"active","quantity":3,"printPriceMoney":{"amount":"530","currency":"USD"},"customerPaidPriceMoney":{"amount":"1530","currency":"USD"},"model":{"id":470,"price":"5.30","priceMoney":{"amount":"530","currency":"USD"},"frontPrice":"5.30","frontPriceMoney":{"amount":"530","currency":"USD"},"backPrice":"0.00","backPriceMoney":{"amount":"0","currency":"USD"},"bothSidesPrice":"5.30","bothSidesPriceMoney":{"amount":"530","currency":"USD"},"options":{{"id":4,"name":"M","value":"m","kz_option_id":3,"attribute":{"id":1,"name":"Size","value":"size"}},{"id":7,"name":"White","value":"#ffffff","kz_option_id":28,"attribute":{"id":2,"name":"Color","value":"color"}}},"template":{"id":13,"name":"Crop Tank Top Girl","price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"product_title":"Crop Tank Top Girl","product_description":"Crop Tank Top Girl","preview":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/397\/thumb\/aa_8384_crop_top_front.png","image":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/289\/original\/aa_8384_crop_top_front.png","imageBack":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/290\/original\/aa_8384_crop_top_back.png","backPrintCanBeAddedOnItsOwn":false,"catalogAttributes":{{"id":1,"value":"size","name":"Size","pivot":{"category_id":2,"attribute_id":1}},{"id":2,"value":"color","name":"Color","pivot":{"category_id":2,"attribute_id":2}}},"category":{"id":2,"name":"Reg Tees","slug":"reg-tee","preview":null,"children":{},"templates":{}}}},"mockups":{{"id":431,"type":"print_file_mockup","typeName":"Print file mockup","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/431\/medium\/variant-mockup-111.jpg","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/431\/original\/variant-mockup-111.jpg"}},"product":{"id":83,"name":"Crop Tank Top Girl","status":"active"}}}}}}),
     *      @Response(400, body={"status":400,"isError":true,"message":"Payment cannot be processed","data":{}}),
     *      @Response(500, body={"isError":true,"message":"This action is unauthorized.","status":500})
     * })
     * @Parameters({
     *      @Parameter("order", type="string", required=true, description="Order ID")
     * })
     */
    public function pay(Request $request, Order $order)
    {
        $this->authorize('pay', $order);

        // prevent duplication payments
            if ($order->isPaymentBlocked()) {
                return $this->response->error(trans('messages.previous_payment_is_still_processing'), 400);
            }

            $order->blockPayment();

        $payment = null;
        try {
            $payment = Payment::payForOrder(auth()->user(), $order);
            $order->unblockPayment();
        }
        catch(Exception $e) {
            $order->unblockPayment();
            return $this->response->error(trans('messages.payment_cannot_be_processed').': '.$e->getMessage(), 400);
        }

        if ((!$payment || !$payment->isPaid()) && !$order->isPaid()) {
            $order->paymentFailed();
            return $this->response->error(trans('messages.payment_cannot_be_processed'), 400);
        }

        $order->placed();
        $order->paid();

        return response()->api([
            'order' => $order->transformFull()
        ]);
    }

    /**
     * Cancel order
     *
     * @Post("/{order}/cancel")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request(identifier="/orders/12/cancel"),
     *      @Response(200, body={"status":200,"isError":false,"message":null,"data":{"order":{"id":12,"order_number":1054,"status":"placed","statusName":"Placed","payment_status":"paid","paymentStatusName":"Paid","fulfillment_status":"unfulfilled","fulfillmentStatusName":"Unfulfilled","refund_status":null,"refund_status_comment":null,"refundStatusName":null,"action_required":null,"actionRequiredDescription":null,"total":{"amount":"4690","currency":"USD"},"subtotal":{"amount":"1590","currency":"USD"},"profit":{"amount":"3000","currency":"USD"},"shipping_method":null,"shippingPrice":{"amount":"3100","currency":"USD"},"shipping_retail_costs":"1.00","customer_paid_price":{"amount":"7690","currency":"USD"},"customerPaidPrice":{"amount":"7690","currency":"USD"},"customerShippingRetailCostsPrice":{"amount":"100","currency":"USD"},"customer_meta":{"id":3210186823,"email":"edward.aws3+test@gmail.com","accepts_marketing":false,"created_at":"2016-05-03T08:37:37-04:00","updated_at":"2016-11-18T19:57:37-05:00","first_name":"Test Name","last_name":"Name","orders_count":0,"state":"disabled","total_spent":"0.00","last_order_id":null,"note":null,"verified_email":true,"multipass_identifier":null,"tax_exempt":false,"tags":"","last_order_name":null,"default_address":{"id":4941309447,"first_name":"Test Name","last_name":"Name","company":null,"address1":"78760 Schroeder Parkways","address2":"123123","city":"West Marianne","province":"Alabama","country":"United States","zip":"35022","phone":null,"name":"Test Name Name","province_code":"AL","country_code":"US","country_name":"United States","default":true}},"shipping_meta":{"first_name":"Aaliyah","last_name":"Jenkins","company":"Dach - Muller","address1":"5982 Darian Island","address2":"","city":"Gustaveland","province":"SC","country":"United States","country_code":"US","zip":"75531","phone":"253-303-0743"},"billing_meta":{"first_name":"","last_name":"","company":"","address1":"","address2":"","city":"","province":"","country":"","country_code":"","zip":"","phone":""},"createdAt":"2016-11-19T00:57:37+00:00","updatedAt":"2016-12-26T17:46:20+00:00","providerUrl":"https:\/\/mntz-2.myshopify.com\/admin\/orders\/4759648970","tracking_number":"TN-123456789","tracking_number":"TN-123456789","policy":{"allowed":{"show":true,"edit":true,"cancel":false,"refund":true,"delete":true,"pay":false},"denied":{"show":true,"edit":true,"cancel":false,"refund":true,"delete":true,"pay":false}},"variants":{{"id":91,"name":"Vapor ladies v-neck dress Girl - L White","status":"active","quantity":2,"printPriceMoney":{"amount":"0","currency":"USD"},"customerPaidPriceMoney":{"amount":"1000","currency":"USD"},"model":{"id":2282,"price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"frontPrice":"0.00","frontPriceMoney":{"amount":"0","currency":"USD"},"backPrice":"0.00","backPriceMoney":{"amount":"0","currency":"USD"},"bothSidesPrice":"0.00","bothSidesPriceMoney":{"amount":"0","currency":"USD"},"options":{{"id":5,"name":"L","value":"l","kz_option_id":4,"attribute":{"id":1,"name":"Size","value":"size"}},{"id":7,"name":"White","value":"#ffffff","kz_option_id":28,"attribute":{"id":2,"name":"Color","value":"color"}}},"template":{"id":39,"name":"Vapor ladies v-neck dress Girl","price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"product_title":"Vapor ladies v-neck dress Girl","product_description":"Vapor ladies v-neck dress Girl","preview":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/358\/thumb\/ladies_v_front_bro_science_empire_lifts_back_web.jpg","image":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/359\/original\/bella_6004_women_crew_front.png","imageBack":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/363\/original\/bella_6004_women_crew_front.png","backPrintCanBeAddedOnItsOwn":true,"catalogAttributes":{{"id":1,"value":"size","name":"Size","pivot":{"category_id":3,"attribute_id":1}},{"id":2,"value":"color","name":"Color","pivot":{"category_id":3,"attribute_id":2}}},"category":{"id":3,"name":"Wild Tees","slug":"wild-tee","preview":null,"children":{},"templates":{}}}},"mockups":{{"id":408,"type":"print_file_mockup","typeName":"Print file mockup","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/408\/medium\/variant-mockup-91.jpg","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/408\/original\/variant-mockup-91.jpg"}},"product":{"id":67,"name":"Vapor ladies v-neck dress Girl","status":"active"}},{"id":90,"name":"Vapor ladies v-neck dress Girl - M White","status":"active","quantity":1,"printPriceMoney":{"amount":"0","currency":"USD"},"customerPaidPriceMoney":{"amount":"1000","currency":"USD"},"model":{"id":2281,"price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"frontPrice":"0.00","frontPriceMoney":{"amount":"0","currency":"USD"},"backPrice":"0.00","backPriceMoney":{"amount":"0","currency":"USD"},"bothSidesPrice":"0.00","bothSidesPriceMoney":{"amount":"0","currency":"USD"},"options":{{"id":4,"name":"M","value":"m","kz_option_id":3,"attribute":{"id":1,"name":"Size","value":"size"}},{"id":7,"name":"White","value":"#ffffff","kz_option_id":28,"attribute":{"id":2,"name":"Color","value":"color"}}},"template":{"id":39,"name":"Vapor ladies v-neck dress Girl","price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"product_title":"Vapor ladies v-neck dress Girl","product_description":"Vapor ladies v-neck dress Girl","preview":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/358\/thumb\/ladies_v_front_bro_science_empire_lifts_back_web.jpg","image":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/359\/original\/bella_6004_women_crew_front.png","imageBack":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/363\/original\/bella_6004_women_crew_front.png","backPrintCanBeAddedOnItsOwn":true,"catalogAttributes":{{"id":1,"value":"size","name":"Size","pivot":{"category_id":3,"attribute_id":1}},{"id":2,"value":"color","name":"Color","pivot":{"category_id":3,"attribute_id":2}}},"category":{"id":3,"name":"Wild Tees","slug":"wild-tee","preview":null,"children":{},"templates":{}}}},"mockups":{{"id":407,"type":"print_file_mockup","typeName":"Print file mockup","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/407\/medium\/variant-mockup-90.jpg","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/407\/original\/variant-mockup-90.jpg"}},"product":{"id":67,"name":"Vapor ladies v-neck dress Girl","status":"active"}},{"id":111,"name":"Crop Tank Top Girl - M White","status":"active","quantity":3,"printPriceMoney":{"amount":"530","currency":"USD"},"customerPaidPriceMoney":{"amount":"1530","currency":"USD"},"model":{"id":470,"price":"5.30","priceMoney":{"amount":"530","currency":"USD"},"frontPrice":"5.30","frontPriceMoney":{"amount":"530","currency":"USD"},"backPrice":"0.00","backPriceMoney":{"amount":"0","currency":"USD"},"bothSidesPrice":"5.30","bothSidesPriceMoney":{"amount":"530","currency":"USD"},"options":{{"id":4,"name":"M","value":"m","kz_option_id":3,"attribute":{"id":1,"name":"Size","value":"size"}},{"id":7,"name":"White","value":"#ffffff","kz_option_id":28,"attribute":{"id":2,"name":"Color","value":"color"}}},"template":{"id":13,"name":"Crop Tank Top Girl","price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"product_title":"Crop Tank Top Girl","product_description":"Crop Tank Top Girl","preview":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/397\/thumb\/aa_8384_crop_top_front.png","image":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/289\/original\/aa_8384_crop_top_front.png","imageBack":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/290\/original\/aa_8384_crop_top_back.png","backPrintCanBeAddedOnItsOwn":false,"catalogAttributes":{{"id":1,"value":"size","name":"Size","pivot":{"category_id":2,"attribute_id":1}},{"id":2,"value":"color","name":"Color","pivot":{"category_id":2,"attribute_id":2}}},"category":{"id":2,"name":"Reg Tees","slug":"reg-tee","preview":null,"children":{},"templates":{}}}},"mockups":{{"id":431,"type":"print_file_mockup","typeName":"Print file mockup","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/431\/medium\/variant-mockup-111.jpg","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/431\/original\/variant-mockup-111.jpg"}},"product":{"id":83,"name":"Crop Tank Top Girl","status":"active"}}}}}}),
     *      @Response(500, body={"isError":true,"message":"This action is unauthorized.","status":500})
     * })
     * @Parameters({
     *      @Parameter("order", type="string", required=true, description="Order ID")
     * })
     */
    public function cancel(Request $request, Order $order)
    {
        $this->authorize('cancel', $order);

        $order->cancel();

        return response()->api([
            'order' => $order->transformFull()
        ]);
    }

    /**
     * Refund order
     *
     * Start refunding order process
     *
     * **Body Attributes:**
     *
     * | name | type |
       | --- | --- |
       |reason | string, required|
     *
     * @Post("/{order}/refund")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request(identifier="/orders/12/refund", body={
                "reason": "Some reason"
     *      }),
     *      @Response(200, body={"status":200,"isError":false,"message":null,"data":{"order":{"id":12,"order_number":1054,"status":"placed","statusName":"Placed","payment_status":"paid","paymentStatusName":"Paid","fulfillment_status":"unfulfilled","fulfillmentStatusName":"Unfulfilled","refund_status":null,"refund_status_comment":null,"refundStatusName":null,"action_required":null,"actionRequiredDescription":null,"total":{"amount":"4690","currency":"USD"},"subtotal":{"amount":"1590","currency":"USD"},"profit":{"amount":"3000","currency":"USD"},"shipping_method":null,"shippingPrice":{"amount":"3100","currency":"USD"},"shipping_retail_costs":"1.00","customer_paid_price":{"amount":"7690","currency":"USD"},"customerPaidPrice":{"amount":"7690","currency":"USD"},"customerShippingRetailCostsPrice":{"amount":"100","currency":"USD"},"customer_meta":{"id":3210186823,"email":"edward.aws3+test@gmail.com","accepts_marketing":false,"created_at":"2016-05-03T08:37:37-04:00","updated_at":"2016-11-18T19:57:37-05:00","first_name":"Test Name","last_name":"Name","orders_count":0,"state":"disabled","total_spent":"0.00","last_order_id":null,"note":null,"verified_email":true,"multipass_identifier":null,"tax_exempt":false,"tags":"","last_order_name":null,"default_address":{"id":4941309447,"first_name":"Test Name","last_name":"Name","company":null,"address1":"78760 Schroeder Parkways","address2":"123123","city":"West Marianne","province":"Alabama","country":"United States","zip":"35022","phone":null,"name":"Test Name Name","province_code":"AL","country_code":"US","country_name":"United States","default":true}},"shipping_meta":{"first_name":"Aaliyah","last_name":"Jenkins","company":"Dach - Muller","address1":"5982 Darian Island","address2":"","city":"Gustaveland","province":"SC","country":"United States","country_code":"US","zip":"75531","phone":"253-303-0743"},"billing_meta":{"first_name":"","last_name":"","company":"","address1":"","address2":"","city":"","province":"","country":"","country_code":"","zip":"","phone":""},"createdAt":"2016-11-19T00:57:37+00:00","updatedAt":"2016-12-26T17:46:20+00:00","providerUrl":"https:\/\/mntz-2.myshopify.com\/admin\/orders\/4759648970","tracking_number":"TN-123456789","policy":{"allowed":{"show":true,"edit":true,"cancel":false,"refund":true,"delete":true,"pay":false},"denied":{"show":true,"edit":true,"cancel":false,"refund":true,"delete":true,"pay":false}},"variants":{{"id":91,"name":"Vapor ladies v-neck dress Girl - L White","status":"active","quantity":2,"printPriceMoney":{"amount":"0","currency":"USD"},"customerPaidPriceMoney":{"amount":"1000","currency":"USD"},"model":{"id":2282,"price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"frontPrice":"0.00","frontPriceMoney":{"amount":"0","currency":"USD"},"backPrice":"0.00","backPriceMoney":{"amount":"0","currency":"USD"},"bothSidesPrice":"0.00","bothSidesPriceMoney":{"amount":"0","currency":"USD"},"options":{{"id":5,"name":"L","value":"l","kz_option_id":4,"attribute":{"id":1,"name":"Size","value":"size"}},{"id":7,"name":"White","value":"#ffffff","kz_option_id":28,"attribute":{"id":2,"name":"Color","value":"color"}}},"template":{"id":39,"name":"Vapor ladies v-neck dress Girl","price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"product_title":"Vapor ladies v-neck dress Girl","product_description":"Vapor ladies v-neck dress Girl","preview":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/358\/thumb\/ladies_v_front_bro_science_empire_lifts_back_web.jpg","image":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/359\/original\/bella_6004_women_crew_front.png","imageBack":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/363\/original\/bella_6004_women_crew_front.png","backPrintCanBeAddedOnItsOwn":true,"catalogAttributes":{{"id":1,"value":"size","name":"Size","pivot":{"category_id":3,"attribute_id":1}},{"id":2,"value":"color","name":"Color","pivot":{"category_id":3,"attribute_id":2}}},"category":{"id":3,"name":"Wild Tees","slug":"wild-tee","preview":null,"children":{},"templates":{}}}},"mockups":{{"id":408,"type":"print_file_mockup","typeName":"Print file mockup","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/408\/medium\/variant-mockup-91.jpg","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/408\/original\/variant-mockup-91.jpg"}},"product":{"id":67,"name":"Vapor ladies v-neck dress Girl","status":"active"}},{"id":90,"name":"Vapor ladies v-neck dress Girl - M White","status":"active","quantity":1,"printPriceMoney":{"amount":"0","currency":"USD"},"customerPaidPriceMoney":{"amount":"1000","currency":"USD"},"model":{"id":2281,"price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"frontPrice":"0.00","frontPriceMoney":{"amount":"0","currency":"USD"},"backPrice":"0.00","backPriceMoney":{"amount":"0","currency":"USD"},"bothSidesPrice":"0.00","bothSidesPriceMoney":{"amount":"0","currency":"USD"},"options":{{"id":4,"name":"M","value":"m","kz_option_id":3,"attribute":{"id":1,"name":"Size","value":"size"}},{"id":7,"name":"White","value":"#ffffff","kz_option_id":28,"attribute":{"id":2,"name":"Color","value":"color"}}},"template":{"id":39,"name":"Vapor ladies v-neck dress Girl","price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"product_title":"Vapor ladies v-neck dress Girl","product_description":"Vapor ladies v-neck dress Girl","preview":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/358\/thumb\/ladies_v_front_bro_science_empire_lifts_back_web.jpg","image":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/359\/original\/bella_6004_women_crew_front.png","imageBack":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/363\/original\/bella_6004_women_crew_front.png","backPrintCanBeAddedOnItsOwn":true,"catalogAttributes":{{"id":1,"value":"size","name":"Size","pivot":{"category_id":3,"attribute_id":1}},{"id":2,"value":"color","name":"Color","pivot":{"category_id":3,"attribute_id":2}}},"category":{"id":3,"name":"Wild Tees","slug":"wild-tee","preview":null,"children":{},"templates":{}}}},"mockups":{{"id":407,"type":"print_file_mockup","typeName":"Print file mockup","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/407\/medium\/variant-mockup-90.jpg","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/407\/original\/variant-mockup-90.jpg"}},"product":{"id":67,"name":"Vapor ladies v-neck dress Girl","status":"active"}},{"id":111,"name":"Crop Tank Top Girl - M White","status":"active","quantity":3,"printPriceMoney":{"amount":"530","currency":"USD"},"customerPaidPriceMoney":{"amount":"1530","currency":"USD"},"model":{"id":470,"price":"5.30","priceMoney":{"amount":"530","currency":"USD"},"frontPrice":"5.30","frontPriceMoney":{"amount":"530","currency":"USD"},"backPrice":"0.00","backPriceMoney":{"amount":"0","currency":"USD"},"bothSidesPrice":"5.30","bothSidesPriceMoney":{"amount":"530","currency":"USD"},"options":{{"id":4,"name":"M","value":"m","kz_option_id":3,"attribute":{"id":1,"name":"Size","value":"size"}},{"id":7,"name":"White","value":"#ffffff","kz_option_id":28,"attribute":{"id":2,"name":"Color","value":"color"}}},"template":{"id":13,"name":"Crop Tank Top Girl","price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"product_title":"Crop Tank Top Girl","product_description":"Crop Tank Top Girl","preview":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/397\/thumb\/aa_8384_crop_top_front.png","image":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/289\/original\/aa_8384_crop_top_front.png","imageBack":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/290\/original\/aa_8384_crop_top_back.png","backPrintCanBeAddedOnItsOwn":false,"catalogAttributes":{{"id":1,"value":"size","name":"Size","pivot":{"category_id":2,"attribute_id":1}},{"id":2,"value":"color","name":"Color","pivot":{"category_id":2,"attribute_id":2}}},"category":{"id":2,"name":"Reg Tees","slug":"reg-tee","preview":null,"children":{},"templates":{}}}},"mockups":{{"id":431,"type":"print_file_mockup","typeName":"Print file mockup","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/431\/medium\/variant-mockup-111.jpg","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/431\/original\/variant-mockup-111.jpg"}},"product":{"id":83,"name":"Crop Tank Top Girl","status":"active"}}}}}}),
     *      @Response(422, body={"status":422,"isError":true,"message":"422 Unprocessable Entity","validationErrors":{"reason":{"The reason field is required."}},"data":{}}),
     *      @Response(500, body={"isError":true,"message":"This action is unauthorized.","status":500})
     * })
     * @Parameters({
     *      @Parameter("order", type="string", required=true, description="Order ID")
     * })
     */
    public function refund(OrderRefundFormRequest $request, Order $order)
    {
        $message = filter_var($request->get('reason'), FILTER_SANITIZE_STRING);

        $this->authorize('refund', $order);

        $order->requestRefund($message);

        return response()->api([
            'order' => $order->transformFull()
        ]);
    }

    /**
     * Attach product variants for order
     *
     * **Body Attributes:**
     *
     * | name | type |
       | --- | --- |
       |variant_ids | array of int, required|
       |quantity | array of int, optional|
       |retail_price | array of decimal, optional|
     *
     * @Post("/{order}/attach-variants")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request(identifier="/orders/19/attach-variants", body={
                "variant_ids": {
                    50
                },
                "quantity": {
                    10
                },
                "retail_price": {
                    9.99
                }
     *      }),
     *      @Response(200, body={"status":200,"isError":false,"message":null,"data":{"order":{"id":19,"order_number":"direct_BxXKGd","status":"draft","statusName":"Draft","payment_status":"pending","paymentStatusName":"Pending","fulfillment_status":"unfulfilled","fulfillmentStatusName":"Unfulfilled","refund_status":null,"refund_status_comment":null,"refundStatusName":null,"action_required":null,"actionRequiredDescription":null,"total":{"amount":"0","currency":"USD"},"subtotal":{"amount":"0","currency":"USD"},"profit":{"amount":"0","currency":"USD"},"shipping_method":null,"shippingPrice":{"amount":"0","currency":"USD"},"shipping_retail_costs":"0.00","customerShippingRetailCostsPrice":{"amount":"0","currency":"USD"},"customer_paid_price":{"amount":"0","currency":"USD"},"customerPaidPrice":{"amount":"0","currency":"USD"},"customer_meta":null,"shipping_meta":{"first_name":"","last_name":"","company":"","address1":"","address2":"","city":"","province":"","country":"","country_code":"","zip":"","phone":""},"billing_meta":{"first_name":"","last_name":"","company":"","address1":"","address2":"","city":"","province":"","country":"","country_code":"","zip":"","phone":""},"createdAt":"2017-01-24T23:58:25+00:00","updatedAt":"2017-01-24T23:58:25+00:00","providerUrl":"https:\/\/mntz-2.myshopify.com\/admin\/orders\/","isPaid":false,"tracking_number":"TN-123456789","policy":{"allowed":{"add":true,"show":true,"edit":true,"edit_variants":true,"cancel":true,"refund":false,"delete":true,"pay":false},"denied":{"add":false,"show":false,"edit":false,"edit_variants":false,"cancel":false,"refund":true,"delete":false,"pay":true}},"variants":{{"id":50,"name":"Crop Tank Top Girl - S White","status":"active","quantity":10,"printPriceMoney":{"amount":"0","currency":"USD"},"customerPaidPriceMoney":{"amount":"999","currency":"USD"},"model":{"id":463,"price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"frontPrice":"0.00","frontPriceMoney":{"amount":"0","currency":"USD"},"backPrice":"0.00","backPriceMoney":{"amount":"0","currency":"USD"},"bothSidesPrice":"0.00","bothSidesPriceMoney":{"amount":"0","currency":"USD"},"options":{{"id":27,"name":"3XL","value":"3xl","kz_option_id":7,"attribute":{"id":1,"name":"Size","value":"size"}},{"id":64,"name":"Neon Rainbow","value":"#d23c7f","kz_option_id":310,"attribute":{"id":2,"name":"Color","value":"color"}}},"template":{"id":12,"name":"Tie Dye - Girls (Neon Rainbow) Girl","price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"product_title":"Tie Dye - Girls (Neon Rainbow) Girl","product_description":"Tie Dye - Girls (Neon Rainbow) Girl","preview":null,"image":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/287\/original\/girl_front_rainbow.png","imageBack":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/288\/original\/girl_back_rainbow.png","backPrintCanBeAddedOnItsOwn":false,"catalogAttributes":{{"id":1,"value":"size","name":"Size","pivot":{"category_id":2,"attribute_id":1}},{"id":2,"value":"color","name":"Color","pivot":{"category_id":2,"attribute_id":2}}},"category":{"id":2,"name":"Reg Tees","slug":"reg-tee","preview":null,"children":{},"templates":{{"id":10,"name":"American Apparel Califoria Pull-Over Hoodie","price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"product_title":"American Apparel Califoria Pull-Over Hoodie Girl","product_description":"American Apparel Califoria Pull-Over Hoodie Girl","preview":null,"image":null,"imageBack":null,"backPrintCanBeAddedOnItsOwn":false,"garment":{"id":2,"name":"Other","slug":"other","position":4,"preview":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/394\/thumb\/1.jpg","garmentGroup":{"id":1,"name":"Women","position":2}}},{"id":11,"name":"T-Shirt","price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"product_title":"T-Shirt Girl","product_description":"T-Shirt Girl","preview":null,"image":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/285\/original\/bella_6004_women_crew_front.png","imageBack":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/286\/original\/bella_6004_women_crew_back.png","backPrintCanBeAddedOnItsOwn":false,"garment":{"id":3,"name":"T-Shirt","slug":"t_shirt","position":1,"preview":null,"garmentGroup":{"id":1,"name":"Women","position":2}}},{"id":13,"name":"Crop Tank Top","price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"product_title":"Crop Tank Top Girl","product_description":"Crop Tank Top Girl","preview":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/397\/thumb\/aa_8384_crop_top_front.png","image":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/289\/original\/aa_8384_crop_top_front.png","imageBack":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/290\/original\/aa_8384_crop_top_back.png","backPrintCanBeAddedOnItsOwn":false,"garment":{"id":1,"name":"Tank-Top","slug":"tank_top","position":2,"preview":null,"garmentGroup":{"id":1,"name":"Women","position":2}}}}}}},"mockups":{{"id":266,"type":"print_file_mockup","typeName":"Print file mockup","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/266\/medium\/variant-mockup-50.jpg","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/266\/original\/variant-mockup-50.jpg"}},"product":{"id":31,"name":"Crop Tank Top Girl","status":"active"}}},"store":{"id":1,"name":"mntz","productsCount":44}}}}),
     *      @Response(500, body={"isError":true,"message":"This action is unauthorized.","status":500})
     * })
     * @Parameters({
     *      @Parameter("order", type="string", required=true, description="Order ID")
     * })
     */
    public function attachVariants(Request $request, Order $order)
    {
        $variant_ids = (array)$request->get('variant_ids');
        $quantity = (array)$request->get('quantity');
        $retail_price = (array)$request->get('retail_price');

        $this->authorize('edit', $order);

        if (!empty($variant_ids)) {
            foreach ($variant_ids as $key => $variant_id) {
                $variant = ProductVariant::findOrFail($variant_id);
                $this->authorize('attach_to_order', $variant);

                if (!$order->variants->contains($variant->id)) {
                    $order->variants()->attach($variant->id, [
                        'quantity' => !empty($quantity[$key]) ? (int)$quantity[$key] : 1,
                        'retail_price' => !empty($retail_price[$key]) ? (float)$retail_price[$key] : 0,
                        'print_price' => Money::i()->amount($variant->printPrice())
                    ]);
                }
            }

            // reload order
            $order = Order::find($order->id);
        }

        return response()->api([
            'order' => $order->transformFull()
        ]);
    }

    /**
     * Update product variant for order
     *
     * **Body Attributes:**
     *
     * | name | type |
       | --- | --- |
       |quantity | int, optional|
       |retail_price | decimal, optional|
     *
     * @Put("/{order}/update-variant/{variant}")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request(identifier="/orders/19/update-variant/50", body={
                "quantity": 5,
                "retail_price": 19.99
     *      }),
     *      @Response(200, body={"status":200,"isError":false,"message":null,"data":{"order":{"id":19,"order_number":"direct_BxXKGd","status":"draft","statusName":"Draft","payment_status":"pending","paymentStatusName":"Pending","fulfillment_status":"unfulfilled","fulfillmentStatusName":"Unfulfilled","refund_status":null,"refund_status_comment":null,"refundStatusName":null,"action_required":null,"actionRequiredDescription":null,"total":{"amount":"0","currency":"USD"},"subtotal":{"amount":"0","currency":"USD"},"profit":{"amount":"0","currency":"USD"},"shipping_method":null,"shippingPrice":{"amount":"0","currency":"USD"},"shipping_retail_costs":"0.00","customerShippingRetailCostsPrice":{"amount":"0","currency":"USD"},"customer_paid_price":{"amount":"0","currency":"USD"},"customerPaidPrice":{"amount":"0","currency":"USD"},"customer_meta":null,"shipping_meta":{"first_name":"","last_name":"","company":"","address1":"","address2":"","city":"","province":"","country":"","country_code":"","zip":"","phone":""},"billing_meta":{"first_name":"","last_name":"","company":"","address1":"","address2":"","city":"","province":"","country":"","country_code":"","zip":"","phone":""},"createdAt":"2017-01-24T23:58:25+00:00","updatedAt":"2017-01-24T23:58:25+00:00","providerUrl":"https:\/\/mntz-2.myshopify.com\/admin\/orders\/","isPaid":false,"tracking_number":"TN-123456789","policy":{"allowed":{"add":true,"show":true,"edit":true,"edit_variants":true,"cancel":true,"refund":false,"delete":true,"pay":false},"denied":{"add":false,"show":false,"edit":false,"edit_variants":false,"cancel":false,"refund":true,"delete":false,"pay":true}},"variants":{{"id":50,"name":"Crop Tank Top Girl - S White","status":"active","quantity":10,"printPriceMoney":{"amount":"0","currency":"USD"},"customerPaidPriceMoney":{"amount":"999","currency":"USD"},"model":{"id":463,"price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"frontPrice":"0.00","frontPriceMoney":{"amount":"0","currency":"USD"},"backPrice":"0.00","backPriceMoney":{"amount":"0","currency":"USD"},"bothSidesPrice":"0.00","bothSidesPriceMoney":{"amount":"0","currency":"USD"},"options":{{"id":27,"name":"3XL","value":"3xl","kz_option_id":7,"attribute":{"id":1,"name":"Size","value":"size"}},{"id":64,"name":"Neon Rainbow","value":"#d23c7f","kz_option_id":310,"attribute":{"id":2,"name":"Color","value":"color"}}},"template":{"id":12,"name":"Tie Dye - Girls (Neon Rainbow) Girl","price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"product_title":"Tie Dye - Girls (Neon Rainbow) Girl","product_description":"Tie Dye - Girls (Neon Rainbow) Girl","preview":null,"image":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/287\/original\/girl_front_rainbow.png","imageBack":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/288\/original\/girl_back_rainbow.png","backPrintCanBeAddedOnItsOwn":false,"catalogAttributes":{{"id":1,"value":"size","name":"Size","pivot":{"category_id":2,"attribute_id":1}},{"id":2,"value":"color","name":"Color","pivot":{"category_id":2,"attribute_id":2}}},"category":{"id":2,"name":"Reg Tees","slug":"reg-tee","preview":null,"children":{},"templates":{{"id":10,"name":"American Apparel Califoria Pull-Over Hoodie","price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"product_title":"American Apparel Califoria Pull-Over Hoodie Girl","product_description":"American Apparel Califoria Pull-Over Hoodie Girl","preview":null,"image":null,"imageBack":null,"backPrintCanBeAddedOnItsOwn":false,"garment":{"id":2,"name":"Other","slug":"other","position":4,"preview":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/394\/thumb\/1.jpg","garmentGroup":{"id":1,"name":"Women","position":2}}},{"id":11,"name":"T-Shirt","price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"product_title":"T-Shirt Girl","product_description":"T-Shirt Girl","preview":null,"image":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/285\/original\/bella_6004_women_crew_front.png","imageBack":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/286\/original\/bella_6004_women_crew_back.png","backPrintCanBeAddedOnItsOwn":false,"garment":{"id":3,"name":"T-Shirt","slug":"t_shirt","position":1,"preview":null,"garmentGroup":{"id":1,"name":"Women","position":2}}},{"id":13,"name":"Crop Tank Top","price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"product_title":"Crop Tank Top Girl","product_description":"Crop Tank Top Girl","preview":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/397\/thumb\/aa_8384_crop_top_front.png","image":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/289\/original\/aa_8384_crop_top_front.png","imageBack":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/290\/original\/aa_8384_crop_top_back.png","backPrintCanBeAddedOnItsOwn":false,"garment":{"id":1,"name":"Tank-Top","slug":"tank_top","position":2,"preview":null,"garmentGroup":{"id":1,"name":"Women","position":2}}}}}}},"mockups":{{"id":266,"type":"print_file_mockup","typeName":"Print file mockup","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/266\/medium\/variant-mockup-50.jpg","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/266\/original\/variant-mockup-50.jpg"}},"product":{"id":31,"name":"Crop Tank Top Girl","status":"active"}}},"store":{"id":1,"name":"mntz","productsCount":44}}}}),
     *      @Response(500, body={"isError":true,"message":"This action is unauthorized.","status":500})
     * })
     * @Parameters({
     *      @Parameter("order", type="string", required=true, description="Order ID"),
     *      @Parameter("variant", type="string", required=true, description="Product Variant ID")
     * })
     */
    public function updateVariant(Request $request, Order $order, ProductVariant $variant)
    {
        $this->authorize('edit', $order);
        $this->authorize('attach_to_order', $variant);

        $quantity = (int)$request->get('quantity');
        $retail_price = (float)$request->get('retail_price');

        if (!empty($quantity) || !empty($retail_price)) {
            $order->variants()->updateExistingPivot($variant->id, [
                'quantity' => $quantity,
                'retail_price' => $retail_price,
                'print_price' => Money::i()->amount($variant->printPrice())
            ]);
        }

        // re-get order
        $order = Order::find($order->id);

        return response()->api([
            'order' => $order->transformFull()
        ]);
    }

    /**
     * Detach product variant for order
     *
     * @Delete("/{order}/detach-variant/{variant}")
     * @Versions({"v1"})
     * @Transaction({
     *      @Request(identifier="/orders/19/detach-variant/50"),
     *      @Response(200, body={"status":200,"isError":false,"message":null,"data":{"order":{"id":19,"order_number":"direct_BxXKGd","status":"draft","statusName":"Draft","payment_status":"pending","paymentStatusName":"Pending","fulfillment_status":"unfulfilled","fulfillmentStatusName":"Unfulfilled","refund_status":null,"refund_status_comment":null,"refundStatusName":null,"action_required":null,"actionRequiredDescription":null,"total":{"amount":"0","currency":"USD"},"subtotal":{"amount":"0","currency":"USD"},"profit":{"amount":"0","currency":"USD"},"shipping_method":null,"shippingPrice":{"amount":"0","currency":"USD"},"shipping_retail_costs":"0.00","customerShippingRetailCostsPrice":{"amount":"0","currency":"USD"},"customer_paid_price":{"amount":"0","currency":"USD"},"customerPaidPrice":{"amount":"0","currency":"USD"},"customer_meta":null,"shipping_meta":{"first_name":"","last_name":"","company":"","address1":"","address2":"","city":"","province":"","country":"","country_code":"","zip":"","phone":""},"billing_meta":{"first_name":"","last_name":"","company":"","address1":"","address2":"","city":"","province":"","country":"","country_code":"","zip":"","phone":""},"createdAt":"2017-01-24T23:58:25+00:00","updatedAt":"2017-01-24T23:58:25+00:00","providerUrl":"https:\/\/mntz-2.myshopify.com\/admin\/orders\/","isPaid":false,"tracking_number":"TN-123456789","policy":{"allowed":{"add":true,"show":true,"edit":true,"edit_variants":true,"cancel":true,"refund":false,"delete":true,"pay":false},"denied":{"add":false,"show":false,"edit":false,"edit_variants":false,"cancel":false,"refund":true,"delete":false,"pay":true}},"variants":{{"id":50,"name":"Crop Tank Top Girl - S White","status":"active","quantity":10,"printPriceMoney":{"amount":"0","currency":"USD"},"customerPaidPriceMoney":{"amount":"999","currency":"USD"},"model":{"id":463,"price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"frontPrice":"0.00","frontPriceMoney":{"amount":"0","currency":"USD"},"backPrice":"0.00","backPriceMoney":{"amount":"0","currency":"USD"},"bothSidesPrice":"0.00","bothSidesPriceMoney":{"amount":"0","currency":"USD"},"options":{{"id":27,"name":"3XL","value":"3xl","kz_option_id":7,"attribute":{"id":1,"name":"Size","value":"size"}},{"id":64,"name":"Neon Rainbow","value":"#d23c7f","kz_option_id":310,"attribute":{"id":2,"name":"Color","value":"color"}}},"template":{"id":12,"name":"Tie Dye - Girls (Neon Rainbow) Girl","price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"product_title":"Tie Dye - Girls (Neon Rainbow) Girl","product_description":"Tie Dye - Girls (Neon Rainbow) Girl","preview":null,"image":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/287\/original\/girl_front_rainbow.png","imageBack":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/288\/original\/girl_back_rainbow.png","backPrintCanBeAddedOnItsOwn":false,"catalogAttributes":{{"id":1,"value":"size","name":"Size","pivot":{"category_id":2,"attribute_id":1}},{"id":2,"value":"color","name":"Color","pivot":{"category_id":2,"attribute_id":2}}},"category":{"id":2,"name":"Reg Tees","slug":"reg-tee","preview":null,"children":{},"templates":{{"id":10,"name":"American Apparel Califoria Pull-Over Hoodie","price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"product_title":"American Apparel Califoria Pull-Over Hoodie Girl","product_description":"American Apparel Califoria Pull-Over Hoodie Girl","preview":null,"image":null,"imageBack":null,"backPrintCanBeAddedOnItsOwn":false,"garment":{"id":2,"name":"Other","slug":"other","position":4,"preview":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/394\/thumb\/1.jpg","garmentGroup":{"id":1,"name":"Women","position":2}}},{"id":11,"name":"T-Shirt","price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"product_title":"T-Shirt Girl","product_description":"T-Shirt Girl","preview":null,"image":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/285\/original\/bella_6004_women_crew_front.png","imageBack":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/286\/original\/bella_6004_women_crew_back.png","backPrintCanBeAddedOnItsOwn":false,"garment":{"id":3,"name":"T-Shirt","slug":"t_shirt","position":1,"preview":null,"garmentGroup":{"id":1,"name":"Women","position":2}}},{"id":13,"name":"Crop Tank Top","price":"0.00","priceMoney":{"amount":"0","currency":"USD"},"product_title":"Crop Tank Top Girl","product_description":"Crop Tank Top Girl","preview":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/397\/thumb\/aa_8384_crop_top_front.png","image":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/289\/original\/aa_8384_crop_top_front.png","imageBack":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/290\/original\/aa_8384_crop_top_back.png","backPrintCanBeAddedOnItsOwn":false,"garment":{"id":1,"name":"Tank-Top","slug":"tank_top","position":2,"preview":null,"garmentGroup":{"id":1,"name":"Women","position":2}}}}}}},"mockups":{{"id":266,"type":"print_file_mockup","typeName":"Print file mockup","thumb":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/266\/medium\/variant-mockup-50.jpg","url":"http:\/\/monetize-social.dev\/system\/App\/Models\/File\/files\/000\/000\/266\/original\/variant-mockup-50.jpg"}},"product":{"id":31,"name":"Crop Tank Top Girl","status":"active"}}},"store":{"id":1,"name":"mntz","productsCount":44}}}}),
     *      @Response(500, body={"isError":true,"message":"This action is unauthorized.","status":500})
     * })
     * @Parameters({
     *      @Parameter("order", type="string", required=true, description="Order ID"),
     *      @Parameter("variant", type="string", required=true, description="Product Variant ID")
     * })
     */
    public function detachVariant(Request $request, Order $order, ProductVariant $variant)
    {
        $this->authorize('edit', $order);
        $this->authorize('attach_to_order', $variant);

        $order->variants()->detach($variant->id);

        // reload order
        $order = Order::find($order->id);

        return response()->api([
            'order' => $order->transformFull()
        ]);
    }

}
