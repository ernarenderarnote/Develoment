<?php



namespace App\Http\Controllers\Dashboard;



use Illuminate\Http\Request;

use Auth;

use App;

use Gate;

use Validator;

use Storage;

use Cache;

use Carbon\Carbon;

use Exception;



use App\Components\Shopify;

use App\Components\Logger;

use App\Components\Money;



use App\Http\Controllers\Controller;

use App\Http\Requests\Dashboard\Store\CreateStoreFormRequest;

use App\Http\Requests\Dashboard\Store\UpdateStoreFormRequest;

use App\Http\Requests\Dashboard\ProductVariant\UpdateOrderProductVariantFormRequest;

use App\Http\Requests\Dashboard\Order\OrderShippingFormRequest;

use App\Http\Requests\Dashboard\Order\OrderReviewFormRequest;

use App\Http\Requests\Dashboard\Order\OrderRefundFormRequest;



use App\Models\Store;

use App\Models\StoreSettings;

use App\Models\Product;

use App\Models\ProductVariant;

use App\Models\ProductModel;

use App\Models\Order;

use App\Models\File;

use App\Models\Payment;



class OrdersController extends Controller

{

    use \App\Http\Controllers\Dashboard\Traits\Orders\WebhookTrait;



    /**

     * Show the application dashboard.

     *

     * @return \Illuminate\Http\Response

     */

    

    public function index(Request $request)

    {



        $search = filter_var($request->get('search'), FILTER_SANITIZE_STRING);

        $store = (int)$request->get('store');

        $status = filter_var($request->get('status'), FILTER_SANITIZE_STRING);

        $per_page = (int)$request->get('per_page', 10);



        $ordersQuery = auth()->user()->orders()

            ->with('statusRevisionHistory')

            ->with('payments')

            ->with('firstSuccessfulPaymentRelation')

            ->whereHas('store', function($query) {

                $query->whereNull('deleted_at');

            })

            ->orderBy('created_at', 'desc')

            ->search($search);



        if ($store) {

            $ordersQuery->where('store_id', $store);

        }



        if ($status) {

            $ordersQuery->where('status', $status);

        }



        $orders = $ordersQuery->paginate($per_page);



        $orders->setPath(action('Dashboard\OrdersController@'.__FUNCTION__, [

            'search' => $search,

            'store' => $store,

            'status' => $status,

            'per_page' => $per_page

        ]));



        $storeIds = auth()->user()->stores->pluck('id')->toArray();



        return view('dashboard.orders.index', [

            'orders' => $orders,

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

     * Create order

     */

    public function create(Request $request)

    {
        $store_id = (int)$request->get('store_id');

        $store = Store::find($store_id);

        $this->authorize('edit', $store);

        $order = new Order();

        $order->store_id = $store->id;

        $this->authorize('add', $order);



        $order->createDirectOrder();



        return redirect('/dashboard/orders/'.$order->id.'/update');

    }



    /**

     * Update view

     */

    public function updateView(Request $request, $order_id)

    {

        $order = Order::findOrFail($order_id);



        if (Gate::allows('edit_variants', $order)) {

            return view('dashboard.orders.update', [

                'order' => $order

            ]);

        }

        else {

            return redirect('/dashboard/orders/'.$order_id.'/review');

        }

    }



    /**

     * Shipping view

     */

    public function shippingView(Request $request, $order_id)

    {

        $order = Order::find($order_id);

        $this->authorize('edit', $order);



        return view('dashboard.orders.update-shipping', [

            'order' => $order

        ]);

    }



        /**

         * Save shipping

         */

        public function saveShipping(OrderShippingFormRequest $request, $order_id)

        {

            $order = Order::find($order_id);

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



            $this->redirectIntent(url('/dashboard/orders/'.$order->id.'/review'));

            return $this->returnSuccess(trans('messages.order_updated'));

        }



    /**

     * Review view

     */

    public function reviewView(Request $request, $order_id)

    {
        
        $order = Order::find($order_id);

        //$this->authorize('edit', $order);

        return view('dashboard.orders.update-review', [

            'order' => $order

        ]);

    }



        /**

         * Save review

         */

        public function saveReview(OrderReviewFormRequest $request, $order_id)

        {

            $order = Order::find($order_id);

            $this->authorize('pay', $order);



            // prevent duplication payments

                if ($order->isPaymentBlocked()) {

                    flash()->error(trans('messages.previous_payment_is_still_processing'));

                    return redirect()->back();

                }



                $order->blockPayment();



            $payment = null;

            try {

                $payment = Payment::payForOrder(auth()->user(), $order);

                $order->unblockPayment();

            }

            catch(Exception $e) {

                $order->unblockPayment();

                return $this->returnError(trans('messages.payment_cannot_be_processed').': '.$e->getMessage());

            }



            if ((!$payment || !$payment->isPaid()) && !$order->isPaid()) {

                $order->paymentFailed();

                return $this->returnError(trans('messages.payment_cannot_be_processed'));

            }



            $order->placed();

            $order->paid();



            $this->redirectIntent(url('/dashboard/orders'));

            return $this->returnSuccess(trans('messages.order_paid'));

        }



    /**

     * Cancel order

     */

    public function cancel(Request $request, $order_id)

    {

        $order = Order::find($order_id);

        $this->authorize('cancel', $order);



        $order->cancel();



        return $this->returnSuccess(trans('messages.order_cancelled'));

    }



    /**

     * Refund order

     */

    public function refund(OrderRefundFormRequest $request, $order_id)

    {

        $message = filter_var($request->get('reason'), FILTER_SANITIZE_STRING);



        $order = Order::find($order_id);

        $this->authorize('refund', $order);



        $order->requestRefund($message);



        return $this->returnSuccess(trans('messages.order_refund_requested'));

    }



    /**

     * View order on Shopify

     */

    public function viewShopify(Request $request, $order_id)

    {

        $order = Order::find($order_id);

        $this->authorize('show', $order);



        return redirect($order->providerUrl());

    }



    public function getWithNewShippingPrice(Request $request, $order_id)

    {

        $order = Order::findOrFail($order_id);

        $this->authorize('edit', $order);



        $country_code = filter_var($request->get('country_code'), FILTER_SANITIZE_STRING);

        $shipping_method = filter_var($request->get('shipping_method'), FILTER_SANITIZE_STRING);



        $order->shipping_method = $shipping_method;

        $order->shipping_meta = array_merge((array)$order->shipping_meta, [

            'country_code' => $country_code

        ]);



        return response()->api(null, [

            'order' => $order->transformFull(),

            'shippingPrices' => [

                Order::SHIPPING_METHOD_FIRST_CLASS => $order->getShippingPrice(Order::SHIPPING_METHOD_FIRST_CLASS),

                Order::SHIPPING_METHOD_PRIORITY_MAIL => $order->getShippingPrice(Order::SHIPPING_METHOD_PRIORITY_MAIL)

            ]

        ]);

    }



    /**

     * Create and attach product variant to order

     */

    //public function addVariant(Request $request, $order_id)

    //{

    //    $order = Order::find($order_id);

    //    if (Gate::denies('edit', $order)) {

    //        return abort(403, trans('messages.not_authorized_to_access_order'));

    //    }

    //

    //    $variant = new ProductVariant();

    //    $result = $variant->createVariant();

    //

    //    $order->variants()->attach($variant->id);

    //

    //    if ( ! $result ) {

    //        return abort(500, trans('messages.order_cannot_be_updated'));

    //    }

    //

    //    $order = Order::find($order->id);

    //    return response()->api(trans('messages.order_updated'), [

    //        'order' => $order->transformFull(),

    //        'variant' => $variant->transformFull()

    //    ]);

    //}



    /**

     * Update product variant to order

     */

    public function updateVariant(UpdateOrderProductVariantFormRequest $request, $order_id, $variant_id)

    {

        $order = Order::findOrFail($order_id);

        $this->authorize('edit', $order);



        $variant = $order->variants->find($variant_id);

        $this->authorize('attach_to_order', $variant);





        $quantity = (int)$request->get('quantity');

        $retail_price = $request->get('retail_price');



        if (!empty($quantity) || !empty($retail_price)) {

            $order->variants()->updateExistingPivot($variant->id, [

                'quantity' => $quantity,

                'retail_price' => $retail_price,

                'print_price' => Money::i()->amount($variant->printPrice())

            ]);

        }



        // re-get order

        $order = Order::find($order->id);

        return response()->api(trans('messages.order_updated'), [

            'order' => $order->transformFull(),

            'variant' => $variant->transformFull()

        ]);

    }



    ///**

    // * Copy product variant in order

    // */

    //public function copyVariant(Request $request, $order_id, $variant_id)

    //{

    //    $order = Order::find($order_id);

    //    if (Gate::denies('edit', $order)) {

    //        return abort(403, trans('messages.not_authorized_to_access_order'));

    //    }

    //

    //    $variant = $order->variants->find($variant_id);

    //    if (Gate::denies('edit', $variant)) {

    //        return abort(403, trans('messages.not_authorized_to_access_product_variant'));

    //    }

    //

    //    $newVariant = $variant->replicate();

    //    $result = $newVariant->push();

    //

    //    //foreach ($variant->files as $file) {

    //    //    $newVariant->files()->save($file, [

    //    //        'type' => $file->pivot->type

    //    //    ]);

    //    //}

    //

    //    $newVariant->orders()->save($order, [

    //        'quantity' => $variant->pivot->quantity,

    //        'retail_price' => $variant->pivot->retail_price,

    //        'print_price' => Money::i()->amount($variant->printPrice())

    //    ]);

    //

    //    if ( ! $result ) {

    //        return abort(500, trans('messages.order_cannot_be_updated'));

    //    }

    //

    //    $order = Order::find($order->id);

    //    return response()->api(trans('messages.order_updated'), [

    //        'order' => $order->transformFull(),

    //        'variant' => $newVariant->transformFull()

    //    ]);

    //}



    /**

     * Attach variant to order

     */

    public function attachVariants(Request $request, $order_id)

    {

        $variant_ids = (array)$request->get('variant_ids');



        $order = Order::findOrFail($order_id);

        $this->authorize('edit', $order);





        if (!empty($variant_ids)) {

            foreach ($variant_ids as $variant_id) {

                $variant = ProductVariant::findOrFail($variant_id);

                $this->authorize('attach_to_order', $variant);



                if (!$order->variants->contains($variant->id)) {

                    $order->variants()->attach($variant->id, [

                        'quantity' => 1,

                        'print_price' => Money::i()->amount($variant->printPrice())

                    ]);

                }

            }



            $order = Order::find($order->id);

        }



        return response()->api([

            'order' => $order->transformFull()

        ]);

    }



    /**

     * Detach product variant from order

     */

    public function detachVariant(Request $request, $order_id, $variant_id)

    {

        $order = Order::find($order_id);

        $variant = ProductVariant::findOrFail($variant_id);



        $this->authorize('edit', $order);

        $this->authorize('attach_to_order', $variant);



        $result = $order->variants()->detach($variant_id);



        $order = Order::find($order->id);

        return response()->api([

            'order' => $order->transformFull()

        ]);

    }



}

