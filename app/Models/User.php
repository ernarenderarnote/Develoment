<?php

namespace App\Models;;

use Laravel\Spark\User as SparkUser;
use Laravel\Spark\Token as SparkToken;
use Braintree\Transaction as BraintreeTransaction;
use App\Components\FractalManager;
use App\Components\Logger;
use App\Transformers\User\UserBriefTransformer;

class User extends SparkUser
{

    use \Illuminate\Notifications\Notifiable;
    use \Illuminate\Database\Eloquent\SoftDeletes;
    use \Venturecraft\Revisionable\RevisionableTrait;
    use \Laravel\Cashier\Billable;
    use Traits\DatetimeTrait;
    use Traits\MetableTrait;

    const STATUS_NEW    = 'new';
    const STATUS_ACTIVE = 'active';
    const STATUS_BANNED = 'banned';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'username',
        'email',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'authy_id',
        'country_code',
        'phone',
        'card_brand',
        'card_last_four',
        'card_country',
        'billing_address',
        'billing_address_line_2',
        'billing_city',
        'billing_zip',
        'billing_country',
        'extra_billing_information',
        'plan'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'trial_ends_at' => 'datetime',
        'uses_two_factor_auth' => 'boolean',
    ];
    public function __construct(array $attributes = [])
    {
        $this->setRawAttributes(array_merge($this->attributes, [
          'status' => static::STATUS_NEW
        ]), true);
        parent::__construct($attributes);
    }

    /************
     * Accessors
     */

    protected $accessors = ['name'];

    public function getNameAttribute()
    {
        return $this->getName();
    }

    /************
     * Mutators
     */

    public function setStatusAttribute($value)
    {
        if (!$this->attributes['status'] && !$value) {
            $this->attributes['status'] = static::STATUS_NEW;
        }
        else {
            $this->attributes['status'] = $value;
        }
    }
    public function stores()
    {
        return $this->hasMany(Store::class);
    }

    public function setPassword($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] = bcrypt($value);
        }
    }

    public function createUser()
    {
        $this->status = static::STATUS_NEW;
        $result = $this->save();

        \Event::fire(new \App\Events\User\UserCreatedEvent($this));

        //return $result;
    }

    
    public function isOwnerOf($related)
    {
        return $this->id == $related->user_id;
    }

    public function isAdmin()
    {
        $emails = explode(',', getenv('ADMIN_USERNAMES'));
        return in_array($this->email, $emails);
    }

    public static function getNotBanned()
    {
        return static::where('status', '!=', static::STATUS_BANNED)
            ->get();
    }

    /************
     * Decorators
     */

    public function getStatusName()
    {
        return static::statusName($this->status);
    }

    public function getName()
    {
        return $this->first_name.' '.$this->last_name;
    }
    
    public function printFiles(){
        return $this->hasMany(File::class)
            ->where('type', File::TYPE_PRINT_FILE);
    }

    public function sourceFiles(){
        return $this->hasMany(FileAttachment::class)
            ->where('type', File::TYPE_SOURCE_FILE);
    }

    public function passwordResets()
    {
        return $this->hasMany(PasswordReset::class, 'email', 'email');
    }

    /*********
     * Helpers
     */

        public static function statusName($status)
        {
            $statuses = static::listStatuses();
            return isset($statuses[$status]) ? $statuses[$status] : null;
        }

        public static function listStatuses()
        {
            return [
                static::STATUS_NEW     => trans('labels.new'),
                static::STATUS_ACTIVE  => trans('labels.active'),
                static::STATUS_BANNED  => trans('labels.banned')
            ];
        }

        public function orders()
        {
            return $this->hasMany(Order::class);
        }

        public function products()
        {
            return $this->hasMany(Product::class);
        }

        public function tokens()
        {
            return $this->hasMany(ApiToken::class);
        }
    /***************
     * Transformers
     */
        public function setToken(SparkToken $token)
        {
            $this->currentToken = ApiToken::where([
                'token' => $token->token
            ])->first();

            return $this;
        }
        public function priceModifiers()
        {
            return $this->hasMany(PriceModifier::class, 'user_id');
        }
        public function transformBrief()
        {
            return FractalManager::serializeItem($this, new UserBriefTransformer);
        }

    /************
     * Functions
     */

        /**
         * @Override
         *
         * Make a "one off" charge on the customer for the given amount.
         *
         * @param  int  $amount
         * @param  array  $options
         * @return \Braintree\Transaction
         */
        public function charge($amount, array $options = [])
        {
            $customer = $this->asBraintreeCustomer();

            $defaultPaymentMethod = null;

            foreach($customer->paymentMethods as $paymentMethod) {
                if ($paymentMethod->default) {
                    $defaultPaymentMethod = $paymentMethod;
                }
            }

            if (!$defaultPaymentMethod) {
                $defaultPaymentMethod = $customer->paymentMethods[0];
            }

            $response = BraintreeTransaction::sale(array_merge([
                'amount' => $amount * (1 + ($this->taxPercentage() / 100)),
                'paymentMethodToken' => $defaultPaymentMethod->token,
                'options' => [
                    'submitForSettlement' => true,
                ],
                'recurring' => true,
            ], $options));

            if (! $response->success) {

                Logger::i(Logger::PAYMENTS)
                    ->error('BraintrBraintreeTransactionee sale error', [
                        'response' => $response
                    ]);

                throw new Exception('Braintree was unable to perform a charge: '.$response->message);
            }

            return $response;
        }


        public function changeStatusTo($status)
        {
            $this->status = $status;
            $result = $this->save();

            // $result check, because we need to trigger only if really changed
            if ($result) {
                \Event::fire(new \App\Events\User\UserStatusChangedEvent($this));
            }

            return $result;
        }

    /**************
     * Collections
     */

    public static function getAdmins()
    {
        $temporary_hardcoded_admin_emails = explode(',', getenv('ADMIN_USERNAMES'));
        return static::whereIn('email', $temporary_hardcoded_admin_emails)
            ->get();
    }

    /***********
     * Checks
     */

    public function isMe($user)
    {
        if ($user) {
            return $this->id == $user->id;
        }
        else {
            return null;
        }
    }

    public function isTester()
    {
        $emails = explode(',', getenv('APP_TESTER_USERNAMES'));
        return in_array($this->email, $emails);
    }

    public function isActive()
    {
        return $this->status == static::STATUS_ACTIVE;
    }

    public function isBanned()
    {
        return $this->status == static::STATUS_BANNED;
    }

    public function isEmailConfirmed()
    {
        return (bool)$this->is_confirmed;
    }

    public function hasPaymentMethod()
    {
        return (bool)$this->braintree_id;
    }

}
