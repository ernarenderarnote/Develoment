<?php

namespace App\Presenters;

use App\Components\Money;

trait ProductVariantPresenter
{
    /**
     * Strings
     */

        public function getStatusName()
        {
            return static::statusName($this->status);
        }

        public function getFullTitle()
        {
            return ($this->product
                ? $this->product->name.' | '
                : ''
            ).$this->name;
        }

    /**
     * Images
     */

        public function providerImage()
        {
            if (isset($this->meta->image_id)) {
                $images = collect($this->product->meta->images);
                $image = $images->where('id', $this->meta->image_id)->first();
                return array_get((array)$image, 'src');
            }
            else {
                return null;
            }
        }

    /**
     * Saved in the pivot table
     */
    public function quantity()
    {
        return ($this->pivot ? $this->pivot->quantity : 0);
    }

    /**
     * Saved in the pivot table
     */
    public function lineItemId()
    {
        return ($this->pivot ? $this->pivot->provider_line_item_id : 0);
    }

    /**
     * Prices
     */

        public function printPriceTax($quantity = 1)
        {
            $price = Money::USD(0);
            if ($this->print_side == static::PRINT_SIDE_ALL) {
                $price = $this->model
                    ? $this->model->bothSidesPriceTax()->multiply($quantity)
                    : Money::USD(0);
            }
            else if ($this->print_side == static::PRINT_SIDE_FRONT) {
                $price = $this->model
                    ? $this->model->frontPriceTax()->multiply($quantity)
                    : Money::USD(0);
            }
            else if ($this->print_side == static::PRINT_SIDE_BACK) {
                $price = $this->model
                    ? $this->model->backPriceTax()->multiply($quantity)
                    : Money::USD(0);
            }

            return $price;
        }

        public function printPriceModifiers($quantity = 1)
        {
            $price = Money::USD(0);
            if ($this->print_side == static::PRINT_SIDE_ALL) {
                $price = $this->model
                    ? $this->model->bothSidesPriceModifier($this->user)->multiply($quantity)
                    : Money::USD(0);
            }
            else if ($this->print_side == static::PRINT_SIDE_FRONT) {
                $price = $this->model
                    ? $this->model->frontPriceModifier($this->user)->multiply($quantity)
                    : Money::USD(0);
            }
            else if ($this->print_side == static::PRINT_SIDE_BACK) {
                $price = $this->model
                    ? $this->model->backPriceModifier($this->user)->multiply($quantity)
                    : Money::USD(0);
            }

            return $price;
        }

        public function printPrice($quantity = 1)
        {
            // when we get the print_pricefrom the order we shouldn't calculate it
            if ($this->pivot && $this->pivot->print_price != 0) {
                $price = Money::USD($this->pivot->print_price)->multiply($quantity);
            }
            else {
                $price = Money::USD(0);
                if ($this->print_side == static::PRINT_SIDE_ALL) {
                    $price = $this->printBothSidesPrice($quantity);
                }
                else if ($this->print_side == static::PRINT_SIDE_FRONT) {
                    $price = $this->printFrontPrice($quantity);
                }
                else if ($this->print_side == static::PRINT_SIDE_BACK) {
                    $price = $this->printBackPrice($quantity);
                }
            }

            return $price;
        }

            private function printFrontPrice($quantity = 1)
            {
                Money::i()->setTaxable($this->isTaxable());
                return $this->model
                    ? $this->model->frontPrice($this->user)->multiply($quantity)
                    : Money::USD(0);
            }

            private function printBackPrice($quantity = 1)
            {
                Money::i()->setTaxable($this->isTaxable());
                return $this->model
                    ? $this->model->backPrice($this->user)->multiply($quantity)
                    : Money::USD(0);
            }

            private function printBothSidesPrice($quantity = 1)
            {
                Money::i()->setTaxable($this->isTaxable());
                return $this->model
                    ? $this->model->bothSidesPrice($this->user)->multiply($quantity)
                    : Money::USD(0);
            }

        public function retailPrice()
        {
            return $this->pivot ? Money::USD($this->pivot->retail_price) : Money::USD(0);
        }

        public function customerPaidPrice()
        {
            return $this->retailPrice();
        }


    public function shippingGroups()
    {
        return (
            $this->model
            && $this->model->template
            && $this->model->template->shippingGroups
        )
            ? $this->model->template->shippingGroups
            : collect([]);
    }

    public function shippingGroup()
    {
        $groups = $this->shippingGroups();
        if (!$groups->isEmpty()) {
            return $groups->first();
        }
        else {
            return null;
        }
    }
}
