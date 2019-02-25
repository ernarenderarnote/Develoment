<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use FractalManager;

use App\Components\Money;
use App\Transformers\ShippingGroup\ShippingGroupFullTransformer;

class ShippingGroup extends Model
{

    // id
    // name
    // full_price_us
    // additional_price_us
    // full_price_ca
    // additional_price_ca
    // full_price_intl
    // additional_price_intl

    protected $table = 'shipping_groups';
    public $timestamps = false;

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    /************
     * Mutators
     */



    /*********
     * Scopes
     */



    /***********
     * Relations
     */

        public function templates()
        {
            return $this->belongsToMany(ProductModelTemplate::class, 'shipping_groups_templates', 'shipping_group_id', 'template_id');
        }

    /***********
     * Checks
     */



    /**********
     * Counters
     */



    /*************
     * Decorators
     */

        public function fullPriceUS($shippingMethod = null)
        {
            if ($shippingMethod == Order::SHIPPING_METHOD_FIRST_CLASS) {
                return Money::i()->parse($this->full_price_us_first_class);
            }
            else if ($shippingMethod == Order::SHIPPING_METHOD_PRIORITY_MAIL) {
                return Money::i()->parse($this->full_price_us_priority_mail);
            }
            else {
                return Money::i()->parse(0);
            }
        }

        public function additionalPriceUS($shippingMethod = null)
        {
            if ($shippingMethod == Order::SHIPPING_METHOD_FIRST_CLASS) {
                return Money::i()->parse($this->additional_price_us_first_class);
            }
            else if ($shippingMethod == Order::SHIPPING_METHOD_PRIORITY_MAIL) {
                return Money::i()->parse($this->additional_price_us_priority_mail);
            }
            else {
                return Money::i()->parse(0);
            }
        }

        public function fullPriceCanada($shippingMethod = null)
        {
            if ($shippingMethod == Order::SHIPPING_METHOD_FIRST_CLASS) {
                return Money::i()->parse($this->full_price_ca_first_class);
            }
            else if ($shippingMethod == Order::SHIPPING_METHOD_PRIORITY_MAIL) {
                return Money::i()->parse($this->full_price_ca_priority_mail);
            }
            else {
                return Money::i()->parse(0);
            }
        }

        public function additionalPriceCanada($shippingMethod = null)
        {
            if ($shippingMethod == Order::SHIPPING_METHOD_FIRST_CLASS) {
                return Money::i()->parse($this->additional_price_ca_first_class);
            }
            else if ($shippingMethod == Order::SHIPPING_METHOD_PRIORITY_MAIL) {
                return Money::i()->parse($this->additional_price_ca_priority_mail);
            }
            else {
                return Money::i()->parse(0);
            }
        }

        public function fullPriceIntl($shippingMethod = null)
        {
            if ($shippingMethod == Order::SHIPPING_METHOD_FIRST_CLASS) {
                return Money::i()->parse($this->full_price_intl_first_class);
            }
            else if ($shippingMethod == Order::SHIPPING_METHOD_PRIORITY_MAIL) {
                return Money::i()->parse($this->full_price_intl_priority_mail);
            }
            else {
                return Money::i()->parse(0);
            }
        }

        public function additionalPriceIntl($shippingMethod = null)
        {
            if ($shippingMethod == Order::SHIPPING_METHOD_FIRST_CLASS) {
                return Money::i()->parse($this->additional_price_intl_first_class);
            }
            else if ($shippingMethod == Order::SHIPPING_METHOD_PRIORITY_MAIL) {
                return Money::i()->parse($this->additional_price_intl_priority_mail);
            }
            else {
                return Money::i()->parse(0);
            }
        }

    /*********
     * Helpers
     */

    /**
     * Transformers
     */

        public function transformFull()
        {
            return FractalManager::serializeItem($this, new ShippingGroupFullTransformer);
        }

    /***********
     * Functions
     */



    /*************
     * Collections
     */


}
