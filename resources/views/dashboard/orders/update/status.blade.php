<div>
    <span class="d-ib ml-5">
        @if ($order->isPlaced())
            <div class="badge badge-warning">
                @lang('labels.order_status'): {{ $order->getStatusName() }}
            </div>
        @elseif ($order->isAccepted())
            <div class="badge badge-info">
                @lang('labels.order_status'): {{ $order->getStatusName() }}
            </div>
        @elseif ($order->isCompleted())
            <div class="badge badge-success">
                @lang('labels.order_status'): {{ $order->getStatusName() }}
            </div>
        @elseif ($order->isCancelled())
            <div class="badge badge-danger">
                @lang('labels.order_status'): {{ $order->getStatusName() }}
            </div>
        @else
            <div class="badge badge-secondary">
                @lang('labels.order_status'): {{ $order->getStatusName() }}
            </div>
        @endif
    </span>

    <span class="d-ib ml-5">
        @if ($order->isPaid())
            <div class="badge badge-success">
                @lang('labels.payment_status'):
                {{ $order->getPaymentStatusName() }}

                @if ($order->firstSuccessfulPayment())
                    (
                        @price($order->firstSuccessfulPayment()->amount()),
                        @app_time_tag($order->firstSuccessfulPayment()->createdAtTz())
                    )
                @endif
            </div>
        @elseif ($order->isPaymentFailed())
            <div class="badge badge-danger">
                @lang('labels.payment_status'): {{ $order->getPaymentStatusName() }}
            </div>
        @else
            <div class="badge badge-secondary">
                @lang('labels.payment_status'): {{ $order->getPaymentStatusName() }}
            </div>
        @endif
    </span>

    <span class="d-ib ml-5">
        @if ($order->fulfillment_status == \App\Models\Order::FULFILLMENT_STATUS_FULFILLED)
            <div class="badge badge-purple">
        @else
            <div class="badge badge-info">
        @endif
            @lang('labels.fulfillment_status'):
            {{ $order->getFulfillmentStatusName() }}

            @if ($order->tracking_number)
                <div class="c-b d-ib">
                    <div
                        class="d-ib c-w js-popover"
                        data-toggle="popover"
                        data-trigger="click"
                        title="@lang('labels.tracking_number')"
                        data-content="{{ $order->tracking_number }}">
                            <i class="fa fa-info-circle"></i>
                    </div>
                </div>
            @endif
        </div>
    </span>

    <span class="d-ib ml-5">
        @if ($order->refund_status == \App\Models\Order::REFUND_STATUS_REQUESTED)
            <div
                class="badge badge-secondary js-popover"
                data-toggle="popover"
                data-trigger="hover"
                title="@lang('labels.your_comment')"
                data-content="{{ $order->refund_status_comment }}">
                    @lang('labels.refund_status'):
                    {{ $order->getRefundStatusName() }}
                    <i class="fa fa-question-circle"></i>
            </div>
        @elseif ($order->refund_status == \App\Models\Order::REFUND_STATUS_DECLINED)
            <div
                class="badge badge-danger js-popover"
                data-toggle="popover"
                data-trigger="hover"
                title="@lang('labels.admin_comment')"
                data-content="{{ $order->refund_status_comment }}">
                    @lang('labels.refund_status'):
                    {{ $order->getRefundStatusName() }}
                    <i class="fa fa-question-circle"></i>
            </div>
        @elseif ($order->refund_status == \App\Models\Order::REFUND_STATUS_REFUNDED)
            <div class="badge badge-purple">
                @lang('labels.refund_status'):
                {{ $order->getRefundStatusName() }}
            </div>
        @else

        @endif
    </span>

    @if(auth()->user()->isAdmin() && $order->isPaid())
        <span class="d-ib ml-5">
            @if($order->notified_api_at)
                <span class="badge badge-success">
                    @lang('labels.sent_to_kz_at'): {{ $order->notified_api_at }}
                </span>
            @else
                <span class="badge badge-secondary">
                    @lang('labels.not_sent_to_kz')
                </span>
            @endif
        </span>
    @endif
</div>
