<?php



namespace App\Models;



use Gate;

use Exception;

use DateTime;

use DateTimeZone;

use DB;

use Geographer;

use FractalManager;

use Hashids;

use Cache;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Builder;



use App\Components\Money;

use App\Transformers\Order\OrderFullTransformer;

use App\Transformers\Order\OrderKZApiTransformer;



class Order extends Base

{

    use \Venturecraft\Revisionable\RevisionableTrait;

    use \Culpa\Traits\Blameable;

    use \Illuminate\Database\Eloquent\SoftDeletes;

    use \Sofa\Eloquence\Eloquence;

    use Traits\HashidTrait;

    use Traits\MetableTrait;

    use Traits\OrderShippingMethodsTrait;



    // id

    // user_id

    // store_id

    // shipping_method

    // shipping_retail_costs

    // provider_order_id

    // status

    // payment_status

    // fulfillment_status

    // price

    // meta

    // customer_meta

    // shipping_meta

    // created_at

    // updated_at



    const STATUS_DRAFT = 'draft'; // created, not paid

    const STATUS_PLACED = 'placed'; // paid

    const STATUS_ACCEPTED = 'accepted'; // accepted by admin

    const STATUS_COMPLETED = 'completed';

    const STATUS_CANCELLED = 'cancelled';



    const PAYMENT_STATUS_PENDING = 'pending';

    const PAYMENT_STATUS_PAID = 'paid';

    const PAYMENT_STATUS_FAILED = 'failed';



    const FULFILLMENT_STATUS_UNFULFILLED = 'unfulfilled';

    const FULFILLMENT_STATUS_PARTIALLY = 'partially-fillfilled';

    const FULFILLMENT_STATUS_FULFILLED = 'fulfilled';



    const REFUND_STATUS_REQUESTED = 'requested';

    const REFUND_STATUS_DECLINED = 'declined';

    const REFUND_STATUS_REFUNDED = 'refunded';



    const SHIPPING_METHOD_FIRST_CLASS = 'first_class';

    const SHIPPING_METHOD_PRIORITY_MAIL = 'priority_mail';



    const SHIPPING_METHOD_KZ_FIRST_CLASS = 1;

    const SHIPPING_METHOD_KZ_FIRST_CLASS_INTL = 3;

    const SHIPPING_METHOD_KZ_PRIORITY_MAIL = 2;

    const SHIPPING_METHOD_KZ_PRIORITY_MAIL_INTL = 4;



    const ACTION_REQUIRED_SHIPPING_METHOD = 'action_required_shipping_method';

    const ACTION_REQUIRED_SHIPPING_GROUP_ASSIGN = 'action_required_shipping_group_assign';

    const ACTION_REQUIRED_AUTO_ORDER_AMOUNT_REACHED = 'action_required_auto_order_amount_reached';

    const ACTION_REQUIRED_VARIANT_PRICE_MISSED = 'action_required_variant_price_missed';



    const META_SHOPIFY_FULFILLMENT_DATA = 'shopify_fulfillment_data';

    const META_SHOPIFY_FULFILLMENT_NOTIFIED_AT = 'shopify_fulfillment_notified_at';

    const META_SHOPIFY_FULFILLMENT_ERROR = 'shopify_fulfillment_error';

    const META_KZAPI_LAST_NOTIFY_ERROR = 'kzapi_last_notify_error';



    protected $table = 'orders';



    protected $fillable = [



    ];



    protected $casts = [

        'meta' => 'array',

        'customer_meta' => 'object'

    ];



    // revisions

    protected $revisionEnabled = true;

    protected $revisionCreationsEnabled = true;

    protected $keepRevisionOf = [

        'status',

        'payment_status',

        'fulfillment_status',

        'refund_status',

        'refund_status_comment'

    ];



    // blameable

    protected $blameable = [

        'created' => 'user_id'

    ];



    // searchable

        protected $searchableColumns = [

            'order_number' => 10,

            'id' => 8,

        ];







    public function __construct(array $attributes = [])

    {

        $this->setRawAttributes(array_merge($this->attributes, [

            'status' => static::STATUS_DRAFT,

            'payment_status' => static::PAYMENT_STATUS_PENDING,

            'fulfillment_status' => static::FULFILLMENT_STATUS_UNFULFILLED

        ]), true);

        parent::__construct($attributes);

    }



    public static function getTableName()

    {

        return with(new static)->getTable();

    }



    /************

     * Accessors

     */



        public function getShippingMetaAttribute($value)

        {

            $value = json_decode($value);



            $filledFields = array_filter((array)$value);

            if (empty($filledFields['country_code'])) {

                $value = $this->billing_meta;

            }



            return (object)array_merge([

                'full_name' => '',

                'first_name' => '',

                'last_name' => '',

                'company' => '',

                'address1' => '',

                'address2' => '',

                'city' => '',

                'province' => '',

                'province_code' => '',

                'country' => '',

                'country_code' => '',

                'zip' => '',

                'phone' => ''

            ], (array)$value);

        }



        public function getBillingMetaAttribute($value)

        {

            $value = json_decode($value);

            return (object)array_merge([

                'full_name' => '',

                'first_name' => '',

                'last_name' => '',

                'company' => '',

                'address1' => '',

                'address2' => '',

                'city' => '',

                'province' => '',

                'province_code' => '',

                'country' => '',

                'country_code' => '',

                'zip' => '',

                'phone' => ''

            ], (array)$value);

        }



        public function getActionRequiredAttribute($value)

        {

            $actionsRequired = json_decode($value, true);



            if (!is_array($actionsRequired)) {

                $actionsRequired = [$value];

            }



            return array_filter((array)$actionsRequired);

        }



    /************

     * Mutators

     */



        public function setStatusAttribute($value)

        {

            if (!$this->status && !$value) {

                $this->attributes['status'] = static::STATUS_DRAFT;

            }

            else {

                $this->attributes['status'] = $value;

            }

        }



        public function setShippingMetaAttribute($value)

        {

            if (is_array($value)) {



                $countryCode = filter_var(array_get($value, 'country_code'), FILTER_SANITIZE_STRING);

                $country = null;

                if ($countryCode) {

                /*Changes*/    $country = '';

                }



                $this->attributes['shipping_meta'] = json_encode(

                    array_merge((array)$this->shipping_meta, [

                        'first_name' => filter_var(array_get($value, 'first_name'), FILTER_SANITIZE_STRING),

                        'last_name' => filter_var(array_get($value, 'last_name'), FILTER_SANITIZE_STRING),

                        'full_name' => filter_var(array_get($value, 'full_name'), FILTER_SANITIZE_STRING),

                        'company' => filter_var(array_get($value, 'company'), FILTER_SANITIZE_STRING),

                        'address1' => filter_var(array_get($value, 'address1'), FILTER_SANITIZE_STRING),

                        'address2' => filter_var(array_get($value, 'address2'), FILTER_SANITIZE_STRING),

                        'city' => filter_var(array_get($value, 'city'), FILTER_SANITIZE_STRING),

                        'province' => filter_var(array_get($value, 'province'), FILTER_SANITIZE_STRING),

                        'country' => $country

                            ? $country->name

                            : '',

                        'country_code' => $countryCode,

                        'zip' => filter_var(array_get($value, 'zip'), FILTER_SANITIZE_STRING),

                        'phone' => filter_var(array_get($value, 'phone'), FILTER_SANITIZE_STRING)

                    ])

                );

            }

        }



        public function setBillingMetaAttribute($value)

        {

            if (is_array($value)) {

                $this->attributes['billing_meta'] = json_encode(

                    array_merge((array)$this->billing_meta, [

                        'first_name' => filter_var(array_get($value, 'first_name'), FILTER_SANITIZE_STRING),

                        'last_name' => filter_var(array_get($value, 'last_name'), FILTER_SANITIZE_STRING),

                        'full_name' => filter_var(array_get($value, 'full_name'), FILTER_SANITIZE_STRING),

                        'company' => filter_var(array_get($value, 'company'), FILTER_SANITIZE_STRING),

                        'address1' => filter_var(array_get($value, 'address1'), FILTER_SANITIZE_STRING),

                        'address2' => filter_var(array_get($value, 'address2'), FILTER_SANITIZE_STRING),

                        'city' => filter_var(array_get($value, 'city'), FILTER_SANITIZE_STRING),

                        'province' => filter_var(array_get($value, 'province'), FILTER_SANITIZE_STRING),

                        'country' => filter_var(array_get($value, 'country'), FILTER_SANITIZE_STRING),

                        'country_code' => filter_var(array_get($value, 'country_code'), FILTER_SANITIZE_STRING),

                        'zip' => filter_var(array_get($value, 'zip'), FILTER_SANITIZE_STRING),

                        'phone' => filter_var(array_get($value, 'phone'), FILTER_SANITIZE_STRING)

                    ])

                );

            }

        }



        public function setActionRequiredAttribute($value)

        {

            $this->attributes['action_required'] = json_encode($value);

        }



    /*********

     * Scopes

     */



        public function scopeOwns($query, $user)

        {

            return $query

                ->where('user_id', $user->id);

        }



        public function scopePaid($query)

        {

            $orders = static::getTableName();

            return $query

                ->where($orders.'.payment_status', static::PAYMENT_STATUS_PAID);

        }



        public function scopeNotRefunded($query)

        {

            $orders = static::getTableName();

            return $query

                ->where(function($query) use($orders) {

                    $query

                        ->whereNull($orders.'.refund_status')

                        ->orWhere($orders.'.refund_status', '!=', static::REFUND_STATUS_REFUNDED);

                });

        }



        public function scopeCreatedAfter($query, $time)

        {

            $orders = static::getTableName();

            return $query

                ->where(DB::raw('UNIX_TIMESTAMP('.$orders.'.created_at)'), '>=', $time);

        }



        public function scopeExcludingDirect($query)

        {

            $orders = static::getTableName();

            return $query

                ->where($orders.'.order_number', 'not like', 'direct_%');

        }



    /***********

     * Relations

     */



        public function user()

        {

           return $this->belongsTo(User::class, 'user_id');

        }



        public function store()

        {

            return $this->belongsTo(Store::class, 'store_id');

        }



        public function variants()

        {

            return $this->belongsToMany(ProductVariant::class, 'orders_product_variants_relations')

                ->withTrashed()

                ->withPivot('quantity')

                ->withPivot('retail_price')

                ->withPivot('provider_line_item_id')

                ->withPivot('print_price');

        } 



        public function payments()

        {

            return $this->hasMany(Payment::class);

        }



        public function firstSuccessfulPaymentRelation()

        {

            return $this->payments()

                ->where('status', Payment::STATUS_SUCCEEDED);

        }



        public function firstSuccessfulPayment()

        {

            return $this->firstSuccessfulPaymentRelation->first();

        }



        public function statusRevisionHistory()

        {

            return $this->morphMany('\Venturecraft\Revisionable\Revision', 'revisionable')

                ->whereIn('key', [

                    'status',

                    'payment_status',

                    'fulfillment_status',

                    'refund_status',

                ])

                ->orderBy('id', SORT_DESC);

        }



    /***********

     * Checks

     */



        public function isDraft()

        {

            return ($this->status == static::STATUS_DRAFT);

        }



        public function isReadyForFullfillment()

        {

            return (

                $this->status == static::STATUS_ACCEPTED

                && $this->isPaid()

            );

        }



        public function isFulfilled()

        {

            return ($this->fulfillment_status == static::FULFILLMENT_STATUS_FULFILLED);

        }



        public function isPlaced()

        {

            return ($this->status == static::STATUS_PLACED);

        }



        public function isAccepted()

        {

            return ($this->status == static::STATUS_ACCEPTED);

        }



        public function isCompleted()

        {

            return ($this->status == static::STATUS_COMPLETED);

        }



        public function isCancelled()

        {

            return ($this->status == static::STATUS_CANCELLED);

        }



        public function isPaid()

        {

            return ($this->payment_status == static::PAYMENT_STATUS_PAID);

        }



        public function isPaymentFailed()

        {

            return ($this->payment_status == static::PAYMENT_STATUS_FAILED);

        }



        public function isRefunded()

        {

            return ($this->refund_status == static::REFUND_STATUS_REFUNDED);

        }



        public function isRefundRequested()

        {

            return ($this->refund_status == static::REFUND_STATUS_REQUESTED);

        }



        public function isActionRequired($action = null)

        {

            if ($action) {

                return in_array($action, $this->action_required);

            }

            else {

                return !empty($this->action_required);

            }

        }



        /**

         * Check if at least one variant has no shipping group

         */

        public function areAllShippingGroupsAssigned()

        {

            foreach($this->variants as $variant) {

                if (

                    !$variant->model

                    || !$variant->model->template

                    || !$variant->model->template->shippingGroups

                    || $variant->model->template->shippingGroups->isEmpty()

                ) {

                    return false;

                }

            }



            return true;

        }



        /**

         * Check if at least one variant has no print price

         */

        public function areAllPricesSet()

        {

            foreach($this->variants as $variant) {

                if (!$variant->modelPriceIsSet()) {

                    return false;

                }

            }



            return true;

        }



        public function isDirectOrder()

        {

            return !$this->isVendorOrder();

        }



        public function isVendorOrder()

        {

            return (bool)$this->provider_order_id;

        }



        public function shipsTo($countryCode)

        {

            return (

                !empty($this->shipping_meta)

                && $this->shipping_meta->country_code == $countryCode

            );

        }



        public function shipsToUS()

        {

            return $this->shipsTo('US');

        }



        public function shipsToCanada()

        {

            return $this->shipsTo('CA');

        }



        public function shipsInternational()

        {

            return !$this->shipsToUS() && !$this->shipsToCanada();

        }



        public function isPaymentBlocked()

        {

            $cacheKey = 'processing_payment_for_order_'.$this->id;

            return Cache::has($cacheKey);

        }



    /**********

     * Counters

     */



        public static function countOrdersStartingFromTime($storeIds, $time)

        {

            return static::whereIn('store_id', $storeIds)

                ->paid()

                ->notRefunded()

                ->excludingDirect()

                ->createdAfter($time)

                ->count();

        }



        public static function countRefunds()

        {

            return static::whereIn('refund_status', [

                    static::REFUND_STATUS_REFUNDED,

                    static::REFUND_STATUS_REQUESTED

                ])

                ->count();

        }



        public static function countRefundRequests()

        {

            return static::whereIn('refund_status', [

                    static::REFUND_STATUS_REQUESTED

                ])

                ->count();

        }



        public static function countWithoutShippingGroups()

        {

            return static::getWithoutShippingGroupsQuery()

                ->count();

        }



        public static function countNotSentToKZAPI()

        {

            return static::getNotSentToKZAPIQuery()

                ->count();

        }



    /*************

     * Decorators

     */



        public function id()

        {

            // TODO: we don't have php-math on live

            return $this->id;

            //return Hashids::connection('orders')->encode($this->id);

        }



        public function getStatusName()

        {

            return static::statusName($this->status);

        }



        public function getPaymentStatusName()

        {

            return static::paymentStatusName($this->payment_status);

        }



        public function getFulfillmentStatusName()

        {

            return static::fulfillmentStatusName($this->fulfillment_status);

        }



        public function getRefundStatusName()

        {

            return static::refundStatusName($this->refund_status);

        }



        public function total()

        {

            if (config('testing.orders') && $this->user->isTester()) {

                return Money::i()->parse(0.01);

            }

            else {

                return $this->subtotal()->add($this->getShippingPrice());

            }

        }



        public function subtotal()

        {

            $subtotal = Money::i()->parse(0);

            if (!empty($this->variants)) {

                foreach($this->variants as $variant) {

                    $subtotal = $subtotal->add($variant->printPrice($variant->quantity()));

                }

            }



            return $subtotal;

        }



        public function taxTotal()

        {

            $tax = Money::i()->parse(0);

            if (!empty($this->variants)) {

                foreach($this->variants as $variant) {

                    $tax = $tax->add($variant->printPriceTax($variant->quantity()));

                }

            }



            return $tax;

        }



        public function totalPriceModifiersPrice()

        {

            $subtotal = Money::i()->parse(0);

            if (!empty($this->variants)) {

                foreach($this->variants as $variant) {

                    $subtotal = $subtotal->add($variant->printPriceModifiers($variant->quantity()));

                }

            }



            return $subtotal;

        }



        public function profit()

        {

            return $this->customerPaidPrice()->subtract($this->total());

        }



        public function customerPaidPrice()

        {

            return Money::i()->parse($this->customer_paid_price);

        }



        public function customerShippingRetailCostsPrice()

        {

            return Money::i()->parse($this->shipping_retail_costs);

        }



        public function orderNumber()

        {

            return $this->order_number;

        }



        public function clientName()

        {

            return ($this->customer_meta ? $this->customer_meta->first_name.' '.$this->customer_meta->last_name : null);

        }



        public function customerMeta($key)

        {

            return ($this->customer_meta ? array_get($this->customer_meta, $key) : null);

        }



        public function getShippingAddressMultiline()

        {

            if (empty($this->shipping_meta)) {

                return null;

            }



            $address = [];



            $address[] = $this->shipping_meta->first_name.' '.$this->shipping_meta->last_name;

            $address[] = $this->shipping_meta->address1;

            $address[] = $this->shipping_meta->address2;

            $address[] = implode(' ', array_filter([$this->shipping_meta->city, $this->shipping_meta->province, $this->shipping_meta->zip]));

            $address[] = $this->shipping_meta->country;



            return implode("\n", $address);

        }



        public function getBillingAddressMultiline()

        {

            if (empty($this->billing_meta)) {

                return null;

            }



            $address = [];



            $address[] = $this->billing_meta->first_name.' '.$this->billing_meta->last_name;

            $address[] = $this->billing_meta->address1;

            $address[] = $this->billing_meta->address2;

            $address[] = implode(' ', array_filter([$this->billing_meta->city, $this->billing_meta->province, $this->billing_meta->zip]));

            $address[] = $this->billing_meta->country;



            return implode("\n", $address);

        }



        public function getActionRequiredDescription($action)

        {

            $actions = $this->actionRequiredDescriptions();

            return isset($actions[$action]) ? $actions[$action] : null;

        }



        public function publicUrl() {

            return url('/dashboard/orders/'.$this->id.'/update');

        }



        public function publicShippingUrl() {

            return url('/dashboard/orders/'.$this->id.'/shipping');

        }



        public function providerUrl()

        {

            return url('https://'.$this->store->domain.'/admin/orders/'.$this->provider_order_id);

        }



        public function getStoreName()

        {

            return $this->store

                ? $this->store->name

                : null;

        }



    /*********

     * Helpers

     */



        public static function statusName($status)

        {

            $statuses = static::listStatuses();

            return array_has($statuses, $status) ? array_get($statuses, $status) : null;

        }



        public static function listStatuses()

        {

            return [

                static::STATUS_DRAFT => trans('labels.order_status__draft'),

                static::STATUS_PLACED => trans('labels.order_status__placed'),

                static::STATUS_ACCEPTED => trans('labels.order_status__accepted'),

                static::STATUS_COMPLETED => trans('labels.order_status__completed'),

                static::STATUS_CANCELLED => trans('labels.order_status__cancelled')

            ];

        }



        public static function paymentStatusName($status)

        {

            $statuses = static::paymentStatuses();

            return array_has($statuses, $status) ? array_get($statuses, $status) : null;

        }



        public static function paymentStatuses()

        {

            return [

                static::PAYMENT_STATUS_PENDING => trans('labels.order_payment_status__pending'),

                static::PAYMENT_STATUS_PAID => trans('labels.order_payment_status__paid'),

                static::PAYMENT_STATUS_FAILED => trans('labels.order_payment_status__failed')

            ];

        }



        public static function fulfillmentStatusName($status)

        {

            $statuses = static::fulfillmentStatuses();

            return array_has($statuses, $status) ? array_get($statuses, $status) : null;

        }



        public static function fulfillmentStatuses()

        {

            return [

                static::FULFILLMENT_STATUS_UNFULFILLED  => trans('labels.order_fulfillment_status__unfulfilled'),

                static::FULFILLMENT_STATUS_PARTIALLY  => trans('labels.order_fulfillment_status__partially-fulfilled'),

                static::FULFILLMENT_STATUS_FULFILLED  => trans('labels.order_fulfillment_status__fulfilled')

            ];

        }



        public static function refundStatusName($status)

        {

            $statuses = static::refundStatuses();

            return array_has($statuses, $status) ? array_get($statuses, $status) : null;

        }



        public static function refundStatuses()

        {

            return [

                static::REFUND_STATUS_REQUESTED  => trans('labels.order_refund_status__requested'),

                static::REFUND_STATUS_DECLINED  => trans('labels.order_refund_status__declined'),

                static::REFUND_STATUS_REFUNDED  => trans('labels.order_refund_status__refunded')

            ];

        }



        public function actionRequiredDescriptions()

        {

            return [

                static::ACTION_REQUIRED_SHIPPING_METHOD

                    => trans('labels.action_required_shipping_method_description'),

                static::ACTION_REQUIRED_SHIPPING_GROUP_ASSIGN

                    => trans('labels.action_required_shipping_group_assign_description'),

                static::ACTION_REQUIRED_AUTO_ORDER_AMOUNT_REACHED

                    => trans('labels.action_required_auto_order_amount_reached_description', [

                        'reset_url' => url('/dashboard/store/'.$this->store->id.'/update')

                    ]),

                static::ACTION_REQUIRED_VARIANT_PRICE_MISSED

                    => trans('labels.action_required_variant_price_missed_description'),

            ];

        }



    /**************

     * Transformers

     */



        public function transformFull()

        {

            return FractalManager::serializeItem($this, new OrderFullTransformer);

        }



        public function transformKZApi()

        {

            return FractalManager::serializeItem($this, new OrderKZApiTransformer);

        }



    /***********

     * Functions

     */



        public static function findByProviderId($provider_order_id)

        {

            return static::where('provider_order_id', $provider_order_id)

                ->first();

        }



        public static function findAllByProviderId($provider_order_id)

        {

            return static::where('provider_order_id', $provider_order_id)

                ->get();

        }



        public static function findByProviderIdAndStore($provider_order_id, $store)

        {

            return static::where('provider_order_id', $provider_order_id)

                ->where('store_id', $store->id)

                ->first();

        }



        public function changeStatus($status)

        {

            $this->status = $status;

            return $this->save();

        }



        public function changePaymentStatus($status)

        {

            $this->payment_status = $status;

            $result = $this->save();



            if ($status == static::PAYMENT_STATUS_PAID) {



                $this->price_modifier = Money::i()->amount($this->totalPriceModifiersPrice());

                $this->total_paid_price = Money::i()->amount($this->total());

                $this->shipping_paid_price = Money::i()->amount($this->getShippingPrice());

                $this->save();



                event(new \App\Events\Order\OrderPaidEvent($this));

            }



            return $result;

        }



        public function changeFulfillmentStatus($status)

        {

            $this->fulfillment_status = $status;

            return $this->save();

        }



        public function fulfilled($trackingNumber = null)

        {

            if ($trackingNumber) {

                $this->tracking_number = $trackingNumber;

            }



            return $this->changeFulfillmentStatus(static::FULFILLMENT_STATUS_FULFILLED);

        }



        public function cancel()

        {

            return $this->changeStatus(static::STATUS_CANCELLED);

        }



        public function restore()

        {

            return $this->changeStatus(static::STATUS_DRAFT);

        }



        public function placed()

        {

            $this->resolveActionRequired();

            return $this->changeStatus(static::STATUS_PLACED);

        }



        public function accept()

        {

            return $this->changeStatus(static::STATUS_ACCEPTED);

        }



        public function complete()

        {

            return $this->changeStatus(static::STATUS_COMPLETED);

        }



        public function paid()

        {

            return $this->changePaymentStatus(static::PAYMENT_STATUS_PAID);

        }



        public function paymentFailed()

        {

            return $this->changePaymentStatus(static::PAYMENT_STATUS_FAILED);

        }



        public function changeRefundStatus($status, $message = '')

        {

            $this->refund_status = $status;

            $this->refund_status_comment = $message;

            $result = $this->save();



            // $result check, because we need to trigger only if really changed

            if ($result) {

                event(new \App\Events\Order\OrderRefundStatusChangedEvent($this));

            }



            return $result;

        }



        public function refunded($message = '')

        {

            return $this->changeRefundStatus(static::REFUND_STATUS_REFUNDED, $message);

        }



        public function requestRefund($message = '')

        {

            return $this->changeRefundStatus(static::REFUND_STATUS_REQUESTED, $message);

        }



        public function createShopifyOrder($store, $variants, $shopifyJson)

        {

            // we need to get variants prices in usd

            $exchangeRate = $shopifyJson->total_price_usd / $shopifyJson->total_price;
            
            $this->user_id = $store->user->id;

            $this->store_id = $store->id;

            $this->customer_paid_price = $shopifyJson->total_price_usd;

            $this->provider_order_id = $shopifyJson->id;

            $this->order_number = $shopifyJson->order_number;

            $this->customer_meta = $shopifyJson->customer;

            $this->shipping_meta = (!empty($shopifyJson->shipping_address)

                ? (array)$shopifyJson->shipping_address

                : $shopifyJson->billing_address)

                + [

                    'full_name' => !empty($shopifyJson->shipping_address)

                        ? $shopifyJson->shipping_address->name

                        : $shopifyJson->billing_address->name

                ];

            $this->billing_meta = (array)$shopifyJson->billing_address;

            $this->shipping_retail_costs = !empty($shopifyJson->shipping_lines) && !empty($shopifyJson->shipping_lines[0])

                ? $shopifyJson->shipping_lines[0]->price

                : 0;

            $this->shipping_method = static::guessShippingMethodByShopifyData($shopifyJson);

            $created_at = new DateTime($shopifyJson->created_at);

            $created_at->setTimezone(new DateTimeZone('UTC'));

            $this->created_at = $created_at->format('Y-m-d H:i:s');



            $result = $this->save();



            foreach ($shopifyJson->line_items as $item) {

                if (in_array($item->variant_id, array_keys($variants))) {

                    $variant = $variants[$item->variant_id];



                    if (!$variant->modelPriceIsSet()) {

                        event(new \App\Events\Order\OrderVariantPriceMissedEvent($this, $variant));

                    }



                    $this->variants()->save($variant, [

                        'provider_line_item_id' => $item->id,

                        'quantity'              => $item->quantity,

                        'retail_price'          => $item->price * $exchangeRate,

                        'print_price'           => Money::i()->amount($variant->printPrice())

                    ]);

                }

            }



            return $result;

        }



        public function createDirectOrder()

        {

            // get id

            $this->save();



            // get hashid id

            $this->order_number = 'direct_'.$this->id();

            $this->save();



            return $this;

        }



        public static function pullFromShopifyJson($store, $json)

        {

            // attach variants

            $variants = [];
            
            foreach ($json->line_items as $item) {
                
                $productVariant = ProductVariant::findByProviderId($item->variant_id);
                if (

                    $productVariant

                    

                ) {



                    $variants[$productVariant->provider_variant_id] = $productVariant;



                }

            }

            

            $order = null;

            if (!empty($variants)) {

                $order = static::findByProviderIdAndStore($json->id, $store);


                if (!$order) {

                    $order = new static();

                    $order->createShopifyOrder($store, $variants, $json);

                }



                if (!$order->isPaid()) {



                    $actionRequired = false;



                    // not all shipping groups assigned

                    if (!$order->areAllShippingGroupsAssigned()) {

                        $actionRequired = true;

                        $order->actionRequired(static::ACTION_REQUIRED_SHIPPING_GROUP_ASSIGN);

                    }



                    // no shipping method selected

                    if (!$order->isShippingMethodSelected()) {

                        if (config('settings.order.auto_confirm.require_shipping_method')) {

                            $actionRequired = true;

                            $order->actionRequired(static::ACTION_REQUIRED_SHIPPING_METHOD);

                        }

                        else {

                            $order->autoSelectShippingMethod();

                        }

                    }


                    // automatic charge limit reached

                    if ($order->store->isAutoOrderAmountReached()) {

                        $actionRequired = true;

                        $order->actionRequired(static::ACTION_REQUIRED_AUTO_ORDER_AMOUNT_REACHED);

                    }



                    // all validations pass, let's process the payment

                    if (

                        $store->getSetting(StoreSettings::SETTING_AUTO_ORDERS_CONFIRM)

                        && !$actionRequired

                        && Gate::allows('pay', $order)

                        && !$order->isPaymentBlocked()

                    ) {

                        $order->blockPayment();

                        $payment = new Payment;

                        $payment->user_id = $store->user->id;

                        $payment->order_id = $order->id;

                        try {

                            $payment = Payment::payForOrder($store->user, $order);

                            $order->unblockPayment();

                        }

                        catch(Exception $e) {

                            $order->unblockPayment();

                        }

                        if (($payment && $payment->isPaid()) || $order->isPaid()) {
                            
                            $order->placed();

                            $order->paid();

                        }

                        else {

                            $order->paymentFailed();

                            event(new \App\Events\Payment\AutoPaymentFailedEvent($payment));

                        }

                    }

                }

            }

            else{



                $order = $productVariant;

            }

            return $order;

        }



        public static function getOrdersProfitStartingFromTime($storeIds, $time)

        {

            $orders = Order::getTableName();

            $payments = Payment::getTableName();



            $result = static::select([

                    DB::raw(

                        '(SUM('.$orders.'.customer_paid_price) - SUM('.$payments.'.amount)) as profit'

                    )

                ])

                ->leftJoin($payments, $payments.'.order_id', '=', $orders.'.id')

                ->whereIn($orders.'.store_id', $storeIds)

                ->paid()

                ->notRefunded()

                ->excludingDirect()

                ->createdAfter($time)

                ->first();



            return Money::i()->parse($result->profit);

        }



        public static function getOrdersCustomerPaidStartingFromTime($storeIds, $time)

        {

            $result = static::select(DB::raw('SUM(customer_paid_price) as price'))

                ->whereIn('store_id', $storeIds)

                ->paid()

                ->notRefunded()

                ->excludingDirect()

                ->createdAfter($time)

                ->first();



            return Money::i()->parse($result->price);

        }



        public static function getOrdersStatsForStore($store_id, $year)

        {

            return static::select(DB::raw('MONTH(created_at) as month, COUNT(id) as orders'))

                ->where('store_id', $store_id)

                ->where(DB::raw('YEAR(created_at)'), $year)

                ->paid()

                ->notRefunded()

                ->excludingDirect()

                ->groupBy(DB::raw('YEAR(created_at), MONTH(created_at)'))

                ->get();

        }



        public static function getIncomeStatsForStore($store_id, $year)

        {

            return static::select(DB::raw('MONTH(created_at) as month, SUM(customer_paid_price) as sum'))

                ->where('store_id', $store_id)

                ->where(DB::raw('YEAR(created_at)'), $year)

                ->paid()

                ->notRefunded()

                ->excludingDirect()

                ->groupBy(DB::raw('YEAR(created_at), MONTH(created_at)'))

                ->orderBy(DB::raw('MONTH(created_at)'))

                ->get();

        }



        public function actionRequired($action)

        {

            if (!$this->isActionRequired($action)) {

                $this->action_required = array_merge($this->action_required, [$action]);

                $result = $this->save();

            }



            if ($action) {

                event(new \App\Events\Order\OrderActionRequiredEvent($this, $action));

            }

        }



        public function resolveActionRequired($action = null)

        {

            if ($action) {

                $resolved_action_required = [$action];

            }

            else {

                $resolved_action_required = $this->action_required;

            }



            $this->action_required = array_diff($this->action_required, $resolved_action_required);

            $result = $this->save();



            if (!empty($resolved_action_required)) {

                foreach($resolved_action_required as $resolved_action) {

                    event(new \App\Events\Order\OrderResolvedActionRequiredEvent($this, $resolved_action));

                }

            }

        }



        public function notifiedKZ()

        {

            $this->notified_api_at = $this->freshTimestamp();

            return $this->save();

        }



        public function blockPayment()

        {

            $cacheKey = 'processing_payment_for_order_'.$this->id;

            return Cache::put($cacheKey, true, Carbon::now()->addMinutes(60));

        }



        public function unblockPayment()

        {

            $cacheKey = 'processing_payment_for_order_'.$this->id;

            Cache::forget($cacheKey);

        }



    /*************

     * Collections

     */



        public static function getRefundsQuery()

        {

            return static::whereIn('refund_status', [

                    static::REFUND_STATUS_REFUNDED,

                    static::REFUND_STATUS_REQUESTED

                ]);

        }



        public static function getWithoutShippingGroupsQuery()

        {

            return static::whereHas('variants', function($query) {

                    $query->whereHas('model', function($query) {

                        $query->whereHas('template', function($query) {

                            $query->whereDoesntHave('shippingGroups');

                        });

                    });

                })

                ->orWhere('action_required', static::ACTION_REQUIRED_SHIPPING_GROUP_ASSIGN);

        }



        public static function getNotSentToKZAPIQuery()

        {

            return static::whereNull('notified_api_at')

                ->where('status', static::STATUS_PLACED)

                ->where(function($q) { $q

                    ->whereNotIn('refund_status', [

                        static::REFUND_STATUS_REFUNDED,

                        static::REFUND_STATUS_REQUESTED

                    ])

                    ->orWhereNull('refund_status');

                });

        }

}

