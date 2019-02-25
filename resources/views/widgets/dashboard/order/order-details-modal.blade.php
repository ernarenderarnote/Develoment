<div
	class="modal fade"
	id="js-order-details-modal-{{ $order->id }}"
	tabindex="-1"
	role="dialog"
	aria-labelledby="js-order-details-modal-{{ $order->id }}-title"
	aria-hidden="true">
	<div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <i class="fa fa-times"></i>
                </button>
                <div class="pull-left pr-30">
                    <h1>#{{ $order->orderNumber() }}</h1>
                    <h3>{{ $order->createdAt('M d Y, h:ia') }}</h3>
                </div>

                <div class="pull-left pl-30">
                    <h1>@price($order->total())</h1>
                    <h3>@choice('labels.n_items', count($order->variants))</h3>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="ta-c">
                <div class="well">
                    @include('dashboard.orders.update.status')
                </div>
            </div>

            @include('dashboard.orders.update.action-required')

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">

                        @include('widgets.dashboard.order.variants')

                        <h2>@lang('labels.order_history')</h2>
                        @include('widgets.dashboard.order.status-history')

                    </div>
                    <div class="col-md-4">
                        <h2>@lang('labels.shipping_address')</h2>
                        {!! nl2br($order->getShippingAddressMultiline()) !!}

                        <h3>@lang('labels.pricing_breakdown')</h3>
                        <div class="row grayscale">
                            <div class="col-md-8 pr-0">
                                <strong>@lang('labels.your_customer_paid'):</strong>
                            </div>
                            <div class="col-md-4 pl-0 ta-nd">
                                @price($order->customerPaidPrice())
                            </div>
                        </div>
                            <div class="row grayscale">
                            <div class="col-md-8 pr-0">
                                <strong>@lang('labels.your_customer_paid_shipping'):</strong>
                            </div>
                            <div class="col-md-4 pl-0 ta-nd">
                                @price($order->customerShippingRetailCostsPrice())
                            </div>
                        </div>
                        <div class="row grayscale">
                            <div class="col-md-8 pr-0">
                                <strong>@lang('labels.your_price'):</strong>
                            </div>
                            <div class="col-md-4 pl-0 ta-nd">
                                @price($order->total())
                            </div>
                        </div>
                        <div class="row grayscale">
                            <div class="col-md-8 pr-0">
                                <strong>@lang('labels.your_profit'):</strong>
                            </div>
                            <div class="col-md-4 pl-0 ta-nd">
                                @price($order->profit())
                            </div>
                        </div>

                        @include('dashboard.orders.update.actions', [
                            'order' => $order
                        ])

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
