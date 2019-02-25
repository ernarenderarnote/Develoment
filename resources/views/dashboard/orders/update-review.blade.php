@extends('layouts.app')

@section('title')
    @lang('labels.update_order')
@stop

@section('bodyClasses', 'page')

@section('content')

@include('dashboard.orders.update.top-bar')

<div class="row">
    <div class="col-md-12">

        <order-review-update-form :editable="{{ (int)(!$order->isPaid()) }}" inline-template="true">

            <table class="table">
                <thead>
                    <tr>
                        <th>@lang('labels.product')</th>
                        <th>&nbsp;</th>
                        <th>@lang('labels.print_file')</th>
                        <th>@lang('labels.size_qty')</th>
                        <th>@lang('labels.price')</th>
                        <th>@lang('labels.retail')</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($order->variants as $variant)
                        <tr>
                            <td class="product-image">
                                @if ($variant->model && $variant->model->template && $variant->model->template->preview)
                                    <img src="{{ $variant->model->template->preview->url() }}" alt="" class="img-responsive" />
                                @else
                                    <img src="{{ url('img/placeholders/placeholder-300x200.png') }}" alt="" class="img-responsive" />
                                @endif
                            </td>
                            <td width="230">
                                <div class="details">

                                    @cannot('purchase', $variant)
                                        <div class="alert alert-danger">
                                            @lang('labels.product_not_available')
                                        </div>
                                    @endcan

                                    <div class="mb-15">
                                        {{ $variant->getFullTitle() }}
                                    </div>

                                    <div class="item-details">
                                        <div class="product-detail-widget">
                                            @include('widgets.dashboard.product.variant-details', [
                                                'variant' => $variant
                                            ])
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td width="288">
                                @include('widgets.dashboard.product.variant-attachments', [
                                    'variant' => $variant
                                ])
                            </td>
                            <td class="w-100">
                                <input
                                    type="text"
                                    name="qty"
                                    value="{{ $variant->quantity() }}"
                                    readonly="true"
                                    class="d-ib form-control w-70"
                                    />
                            </td>
                            <td class="price">
                                <div class="pt-5">
                                    @can('purchase', $variant)
                                        @price($variant->printPrice())
                                    @else
                                        &mdash;
                                    @endcan
                                </div>
                            </td>
                            <td class="w-100">
                                <span class="d-ib">$</span>
                                <input
                                    type="text"
                                    value="{{ \App\Components\Money::i()->format($variant->retailPrice()) }}"
                                    readonly="true"
                                    class="d-ib form-control w-70"
                                    />
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>

            <div class="row margin-bottom-30">
                <div class="col-md-4 margin-top-30">
                    <h4 class="no-margin-top">@lang('labels.shipping_from')</h4>

                    <div>
                        {!! $order->store->getShippingAddressFormatted() !!}
                    </div>
                </div>

                <div class="col-md-4 margin-top-30">
                    <h4 class="no-margin-top">
                        @lang('labels.shipping_to')
                        <a v-if="editable" href="{{ url('/dashboard/orders/'.$order->id.'/shipping') }}">
                            @lang('actions.change')
                        </a>
                    </h4>

                    <div>
                        {!! nl2br($order->getShippingAddressMultiline()) !!}
                    </div>
                </div>

                <div class="col-md-4 margin-top-30">
                    <h4 class="no-margin-top">
                        @lang('labels.shipping_&_handling')
                        <a v-if="editable" href="{{ url('/dashboard/orders/'.$order->id.'/shipping') }}">
                            @lang('actions.change')
                        </a>
                    </h4>
                    <p>
                        @lang('labels.shipping_method'):
                        @if ($order->getShippingMethodName())
                            {{ $order->getShippingMethodName() }}
                        @else
                            <span class="color-warning">
                                @lang('labels.required')
                            </span>
                        @endif
                    </p>
                    <p>@lang('labels.price'): @price($order->getShippingPrice())</p>
                </div>
            </div>

            @if($order->isFulfilled())
                <div class="col-md-6 col-md-offset-3 mt-15">
                    <div class="alert alert-info">
                        @lang('labels.tracking_number'): <b>{{ $order->tracking_number }}</b>
                    </div>
                </div>
            @endif

            {!! BootForm::open()
                ->addClass('mt-30 row')
                ->attribute('@submit', 'submitted = true')
                ->post() !!}

                <div class="col-md-6 col-md-offset-3" v-if="editable">
                    <div class="form-group ta-c">
                        <h4 class="ta-st">@lang('labels.payment_method')</h4>

                        <!-- Paypal Indicator -->
                        <span v-if="billable.paypal_email">
                            <i class="fa fa-btn fa-paypal"></i>
                            @{{ billable.paypal_email }}
                        </span>

                        <!-- Credit Card Indicator -->
                        <span v-if="billable.card_last_four">
                            <i class="fa fa-btn @{{ cardIcon }}"></i>
                            ************@{{ billable.card_last_four }}
                        </span>

                        <div
                            v-if="!billable.paypal_email && !billable.card_last_four"
                            class="alert alert-warning mb-0">
                            @lang('labels.need_payment_method_to_complete_order')
                        </div>

                        <a href="{{ url('/settings#/payment-method') }}" class="btn btn-link">
                            <span v-if="billable.paypal_email || billable.card_last_four">
                                @lang('actions.change')
                            </span>
                            <span v-if="!billable.paypal_email && !billable.card_last_four">
                                @lang('actions.add_payment_method')
                            </span>
                        </a>

                    </div>
                </div>

                @include('dashboard.orders.update.totals')

                <div class="row">
                    <div class="col-md-6 col-md-offset-3 ta-c">
                        @can('pay', $order)
                            {!! BootForm::submit(trans('actions.complete_order'))
                                ->attribute('class', 'btn btn-danger')
                                ->attribute(':disabled', '(!billable.paypal_email && !billable.card_last_four) || submitted')
                                !!}
                        @endcan
                    </div>
                </div>

            {!! BootForm::close() !!}

            <div class="row">
                <div class="col-md-6 col-md-offset-3 ta-c">
                    @include('dashboard.orders.update.actions', [
                        'order' => $order,
                        'showDetails' => false
                    ])
                </div>
            </div>

        </order-review-update-form>

    </div>
</div>

@stop
