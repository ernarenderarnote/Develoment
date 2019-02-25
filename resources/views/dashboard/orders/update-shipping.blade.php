@extends('layouts.app')

@section('title')
    @lang('labels.update_order')
@stop

@section('bodyClasses', 'page')

@section('content')

@include('dashboard.orders.update.top-bar')

<div class="row">
    <div class="col-md-12">

        <order-shipping-update-form
            :editable="{{ (int)(!$order->isPaid()) }}"
            inline-template="true">
            <spinner v-ref:shipping-price-spinner size="md"></spinner>

            {!! BootForm::open()
                ->attribute('id', 'js-update-store-form')
                ->addClass('mt-20')
                ->post() !!}

                <h4>@lang('labels.shipping_address')</h4>

                {!! BootForm::bind((array)$order->shipping_meta + [
                    'shipping_method' => $order->shipping_method
                ]) !!}

                @include('dashboard.orders.update.address-form')


                <div class="row">
                    <div class="col-xs-12">
                        <h4>@lang('labels.select_shipping_method')</h4>

                        <button-group :value.sync="order.shipping_method" class="hide-icheck">
                            <radio value="first_class">
                                @{{ shippingPrices[constants.Models.Order.SHIPPING_METHOD_FIRST_CLASS] | currency }}
                                @lang('labels.first_class')
                            </radio>
                            <radio value="priority_mail">
                                @{{ shippingPrices[constants.Models.Order.SHIPPING_METHOD_PRIORITY_MAIL] | currency }}
                                @lang('labels.priority_mail')
                            </radio>
                        </button-group>

                        <input
                            v-model="order.shipping_method"
                            type="text"
                            class="hidden"
                            name="shipping_method"
                            />
                    </div>
                </div>

                @include('dashboard.orders.update.totals')

                <div class="row">
                    <div class="col-xs-12 ta-c">
                        @can('edit_shipping_info', $order)
                            {!! BootForm::submit(trans('actions.continue_to_review'))
                                ->attribute('class', 'btn btn-danger') !!}
                        @endcan
                    </div>
                </div>

            {!! BootForm::close() !!}

        </order-shipping-update-form>
    </div>
</div>

@stop
